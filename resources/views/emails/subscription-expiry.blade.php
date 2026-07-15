<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: 'Tahoma', sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="background-color: #ffc107; padding: 30px; text-align: center;">
            <h1 style="color: #333333; margin: 0; font-size: 24px;">کارت‌اکس</h1>
        </div>
        <div style="padding: 30px;">
            <h2 style="color: #333333; font-size: 20px; margin-bottom: 15px;">انقضای اشتراک</h2>
            <p style="color: #666666; line-height: 1.8; font-size: 16px;">
                سلام {{ $user->name }}،
            </p>
            <p style="color: #666666; line-height: 1.8; font-size: 16px;">
                اشتراک شما به زودی منقضی می‌شود. برای ادامه استفاده از امکانات، لطفاً اشتراک خود را تمدید کنید.
            </p>
            <div style="background-color: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0; border: 1px solid #ffc107;">
                <p style="margin: 0; color: #856404;">
                    <strong>تاریخ انقضا:</strong> {{ \Morilog\Jalali\Jalalian::fromCarbon($subscription->ends_at)->format('Y/m/d') }}
                </p>
            </div>
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('dashboard.subscription.index') }}" style="background-color: #0d6efd; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-size: 16px;">
                    تمدید اشتراک
                </a>
            </div>
        </div>
    </div>
</body>
</html>
