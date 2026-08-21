<!-- resources/views/emails/purchase-order.blade.php -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="es">
<head>
<title>Orden de Compra</title>
<meta charset="UTF-8" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!--[if !mso]><!---->
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!--<![endif]-->
<meta name="x-apple-disable-message-reformatting" content="" />
<meta content="target-densitydpi=device-dpi" name="viewport" />
<meta content="true" name="HandheldFriendly" />
<meta content="width=device-width" name="viewport" />
<meta name="format-detection" content="telephone=no, date=no, address=no, email=no, url=no" />
<style type="text/css">
table {
border-collapse: separate;
table-layout: fixed;
mso-table-lspace: 0pt;
mso-table-rspace: 0pt
}
table td {
border-collapse: collapse
}
.ExternalClass {
width: 100%
}
.ExternalClass,
.ExternalClass p,
.ExternalClass span,
.ExternalClass font,
.ExternalClass td,
.ExternalClass div {
line-height: 100%
}
body, a, li, p, h1, h2, h3 {
-ms-text-size-adjust: 100%;
-webkit-text-size-adjust: 100%;
}
html {
-webkit-text-size-adjust: none !important
}
body {
min-width: 100%;
Margin: 0px;
padding: 0px;
}
body, #innerTable {
-webkit-font-smoothing: antialiased;
-moz-osx-font-smoothing: grayscale
}
#innerTable img+div {
display: none !important
}
img {
Margin: 0;
padding: 0;
-ms-interpolation-mode: bicubic
}
h1, h2, h3, p, a {
overflow-wrap: normal;
white-space: normal;
word-break: break-word
}
a {
text-decoration: none
}
h1, h2, h3, p {
min-width: 100%!important;
width: 100%!important;
max-width: 100%!important;
display: inline-block!important;
border: 0;
padding: 0;
margin: 0
}
a[x-apple-data-detectors] {
color: inherit !important;
text-decoration: none !important;
font-size: inherit !important;
font-family: inherit !important;
font-weight: inherit !important;
line-height: inherit !important
}
u + #body a {
color: inherit;
text-decoration: none;
font-size: inherit;
font-family: inherit;
font-weight: inherit;
line-height: inherit;
}
a[href^="mailto"],
a[href^="tel"],
a[href^="sms"] {
color: inherit;
text-decoration: none
}
</style>
<style type="text/css">
@media (max-width: 480px) {
.hm { display: none!important }
.t48,.t95{padding-left:20px!important;padding-right:20px!important}
}
</style>
<!--[if !mso]><!---->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet" type="text/css" />
<!--<![endif]-->
<!--[if mso]>
<xml>
<o:OfficeDocumentSettings>
<o:AllowPNG/>
<o:PixelsPerInch>96</o:PixelsPerInch>
</o:OfficeDocumentSettings>
</xml>
<![endif]-->
</head>
<body id="body" class="t102" style="min-width:100%;Margin:0px;padding:0px;background-color:#E3E3E3;">
<div class="t101" style="background-color:#E3E3E3;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td class="t100" style="font-size:0;line-height:0;mso-line-height-rule:exactly;background-color:#E3E3E3;" valign="top" align="center">
<!--[if mso]>
<v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false">
<v:fill color="#E3E3E3"/>
</v:background>
<![endif]-->
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" id="innerTable">
<tr>
<td class="t52" align="center">
<table class="t51" role="presentation" cellpadding="0" cellspacing="0" style="Margin-left:auto;Margin-right:auto;">
<tr>
<td width="600" class="t50" style="width:600px;">
<table class="t49" role="presentation" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
<tr>
<td class="t48" style="border-top:5px solid #132873;background-color:#FFFFFF;padding:35px 50px 35px 50px;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width:100% !important;">

<!-- Saludo -->
<tr>
<td class="t12" align="center">
<table class="t11" role="presentation" cellpadding="0" cellspacing="0" style="Margin-left:auto;Margin-right:auto;">
<tr>
<td width="500" class="t10" style="width:600px;">
<table class="t9" role="presentation" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
<tr>
<td class="t8">
<p class="t7" style="margin:0;font-family:Roboto,BlinkMacSystemFont,Segoe UI,Helvetica Neue,Arial,sans-serif;line-height:24px;font-weight:700;font-size:18px;color:#132873;text-align:left;">
Estimado(a) {{ $purchaseOrder->supplier?->name ?? 'Proveedor' }},
</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>

<tr><td><div class="t13" style="line-height:20px;font-size:1px;display:block;">&nbsp;</div></td></tr>

<!-- Cuerpo del correo -->
<tr>
<td class="t19" align="center">
<table class="t18" role="presentation" cellpadding="0" cellspacing="0" style="Margin-left:auto;Margin-right:auto;">
<tr>
<td width="500" class="t17" style="width:600px;">
<table class="t16" role="presentation" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
<tr>
<td class="t15">
<p class="t14" style="margin:0;font-family:Roboto,BlinkMacSystemFont,Segoe UI,Helvetica Neue,Arial,sans-serif;line-height:22px;font-weight:400;font-size:15px;color:#333333;text-align:left;">
Le informamos que se ha generado la <strong>Orden de Compra {{ $purchaseOrder->order_number ?? '#' . str_pad($purchaseOrder->id, 5, '0', STR_PAD_LEFT) }}</strong> correspondiente a su cuenta.
</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>

<tr><td><div class="t20" style="line-height:16px;font-size:1px;display:block;">&nbsp;</div></td></tr>

<tr>
<td class="t26" align="center">
<table class="t25" role="presentation" cellpadding="0" cellspacing="0" style="Margin-left:auto;Margin-right:auto;">
<tr>
<td width="500" class="t24" style="width:600px;">
<table class="t23" role="presentation" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
<tr>
<td class="t22">
<p class="t21" style="margin:0;font-family:Roboto,BlinkMacSystemFont,Segoe UI,Helvetica Neue,Arial,sans-serif;line-height:22px;font-weight:400;font-size:15px;color:#333333;text-align:left;">
Agradecemos revisar el documento adjunto o descargarlo a través del siguiente botón para proceder con el procesamiento y entrega de los ítems solicitados.
</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>

<tr><td><div class="t28" style="line-height:30px;font-size:1px;display:block;">&nbsp;</div></td></tr>

<!-- Boton Descargar PDF -->
<tr>
<td class="t33" align="left">
<table class="t32" role="presentation" cellpadding="0" cellspacing="0" style="Margin-right:auto;">
<tr>
<td width="180" class="t31" style="width:180px;">
<table class="t30" role="presentation" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
<tr>
<td class="t29" style="background-color:#132873;text-align:center;line-height:40px;border-radius:6px;">
<a class="t27" href="{{ $pdfUrl ?? '#' }}" style="display:block;margin:0;font-family:Roboto,BlinkMacSystemFont,Segoe UI,Helvetica Neue,Arial,sans-serif;line-height:40px;font-weight:700;font-size:15px;text-decoration:none;color:#FFFFFF;text-align:center;" target="_blank">Descargar PDF</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>

<tr><td><div class="t34" style="line-height:30px;font-size:1px;display:block;">&nbsp;</div></td></tr>

<!-- Despedida -->
<tr>
<td class="t40" align="center">
<table class="t39" role="presentation" cellpadding="0" cellspacing="0" style="Margin-left:auto;Margin-right:auto;">
<tr>
<td width="500" class="t38" style="width:600px;">
<table class="t37" role="presentation" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
<tr>
<td class="t36">
<p class="t35" style="margin:0;font-family:Roboto,BlinkMacSystemFont,Segoe UI,Helvetica Neue,Arial,sans-serif;line-height:22px;font-weight:400;font-size:15px;color:#333333;text-align:left;">
Agradecemos de antemano su atención y colaboración.
</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>

<tr><td><div class="t41" style="line-height:20px;font-size:1px;display:block;">&nbsp;</div></td></tr>

<tr>
<td class="t47" align="center">
<table class="t46" role="presentation" cellpadding="0" cellspacing="0" style="Margin-left:auto;Margin-right:auto;">
<tr>
<td width="500" class="t45" style="width:600px;">
<table class="t44" role="presentation" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
<tr>
<td class="t43">
<p class="t42" style="margin:0;font-family:Roboto,BlinkMacSystemFont,Segoe UI,Helvetica Neue,Arial,sans-serif;line-height:22px;font-weight:700;font-size:15px;color:#132873;text-align:left;">
Atentamente,<br>
<span style="font-weight:400;color:#555555;">{{ config('app.name', 'G-ERP System') }}</span>
</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>

</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>

<!-- Pie de Pagina Limpio -->
<tr>
<td class="t99" align="center">
<table class="t98" role="presentation" cellpadding="0" cellspacing="0" style="Margin-left:auto;Margin-right:auto;">
<tr>
<td width="600" class="t97" style="width:600px;">
<table class="t96" role="presentation" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
<tr>
<td class="t95" style="background-color:#F5F5F5;padding:25px 50px 30px 50px;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width:100% !important;">
<tr>
<td class="t87" align="center">
<table class="t86" role="presentation" cellpadding="0" cellspacing="0" style="Margin-left:auto;Margin-right:auto;">
<tr>
<td width="500" class="t85" style="width:600px;">
<table class="t84" role="presentation" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
<tr>
<td class="t83">
<p class="t82" style="margin:0;font-family:Roboto,BlinkMacSystemFont,Segoe UI,Helvetica Neue,Arial,sans-serif;line-height:16px;font-weight:400;font-size:12px;color:#888888;text-align:center;">
Este es un mensaje generado automáticamente por el sistema {{ config('app.name', 'G-ERP') }}. Por favor no responda directamente a este correo.
</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>

</table>
</td>
</tr>
</table>
</div>
</body>
</html>