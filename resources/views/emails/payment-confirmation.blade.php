<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: 'Tahoma', sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="background-color: #198754; padding: 30px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">کارت‌اکس</h1>
        </div>
        <div style="padding: 30px;">
            <h2 style="color: #333333; font-size: 20px; margin-bottom: 15px;">تأیید پرداخت</h2>
            <p style="color: #666666; line-height: 1.8; font-size: 16px;">
                سلام {{ $user->name }}،
            </p>
            <p style="color: #666666; line-height: 1.8; font-size: 16px;">
                پرداخت شما با موفقیت انجام شد.
            </p>
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; color: #666666;">شماره پیگیری:</td>
                        <td style="padding: 8px 0; text-align: left; font-weight: bold;">{{ $payment->gateway_tracking_code ?? $payment->id }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #666666;">مبلغ:</td>
                        <td style="padding: 8px 0; text-align: left; font-weight: bold;">{{ number_format($payment->amount) }} تومان</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #666666;">تاریخ:</td>
                        <td style="padding: 8px 0; text-align: left; font-weight: bold;">{{ \Morilog\Jalali\Jalalian::fromCarbon($payment->paid_at)->format('Y/m/d H:i') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #666666;">طرح:</td>
                        <td style="padding: 8px 0; text-align: left; font-weight: bold;">{{ $payment->plan->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('dashboard.subscription.index') }}" style="background-color: #0d6efd; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-size: 16px;">
                    مشاهده اشتراک
                </a>
            </div>
        </div>
    </div>
</body>
</html>
