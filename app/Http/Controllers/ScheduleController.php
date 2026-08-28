<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Events\ScheduleActionBroadcast;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user && $user->role_id === 1) {
            $events = Schedule::with('user')->get();
        } else {
            $events = Schedule::where('user_id', $user->id)->with('user')->get();
        }

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'event_date'  => 'required|date',
            'event_time'  => 'required',
            'color'       => 'required|string|max:20',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        $schedule = Schedule::create($validated);
        
        $schedule->load('user');

        broadcast(new ScheduleActionBroadcast($schedule, 'created'))->toOthers();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'event' => $schedule]);
        }

        return redirect()->back()->with('success', 'Evento agendado correctamente.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $user = auth()->user();
        if ($user->role_id !== 1 && $schedule->user_id !== $user->id) {
            abort(403, 'No autorizado.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'event_date'  => 'required|date',
            'event_time'  => 'required',
            'color'       => 'required|string|max:20',
            'description' => 'nullable|string',
        ]);

        $schedule->update($validated);
        
        $schedule->load('user');

        broadcast(new ScheduleActionBroadcast($schedule, 'updated'))->toOthers();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'event' => $schedule]);
        }

        return redirect()->back()->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Schedule $schedule)
    {
        $user = auth()->user();
        if ($user->role_id !== 1 && $schedule->user_id !== $user->id) {
            abort(403, 'No autorizado.');
        }

        $scheduleData = $schedule->load('user');

        $schedule->delete();

        broadcast(new ScheduleActionBroadcast($scheduleData, 'deleted'))->toOthers();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Evento eliminado correctamente.');
    }
}