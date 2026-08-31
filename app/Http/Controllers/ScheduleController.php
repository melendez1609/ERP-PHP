<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Events\ScheduleActionBroadcast;
use Illuminate\Http\Request;
use App\Models\SystemLog;

class ScheduleController extends Controller
{
    public function index()
    {
        $events = Schedule::with('user')->get();

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

        SystemLog::log('AGENDA_EVENTO_CREADO', [
            'schedule_id' => $schedule->id,
            'title'       => $schedule->title,
            'event_date'  => $schedule->event_date,
            'event_time'  => $schedule->event_time,
        ]);

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

        SystemLog::log('AGENDA_EVENTO_ACTUALIZADO', [
            'schedule_id' => $schedule->id,
            'title'       => $schedule->title,
            'event_date'  => $schedule->event_date,
            'event_time'  => $schedule->event_time,
        ]);

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
        $scheduleId = $schedule->id;
        $scheduleTitle = $schedule->title;

        $schedule->delete();

        broadcast(new ScheduleActionBroadcast($scheduleData, 'deleted'))->toOthers();

        SystemLog::log('AGENDA_EVENTO_ELIMINADO', [
            'schedule_id' => $scheduleId,
            'title'       => $scheduleTitle,
        ]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Evento eliminado correctamente.');
    }
}