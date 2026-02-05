<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.email.otp_subject') }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #0a0a0f;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 100%; max-width: 600px; border-collapse: collapse;">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 30px; text-align: center; background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); border-radius: 16px 16px 0 0;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: bold;">
                                Collect Resayil
                            </h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px; background-color: #1a1a24; border: 1px solid rgba(168, 85, 247, 0.2);">
                            <p style="margin: 0 0 20px; color: #e5e5e5; font-size: 16px;">
                                {{ __('messages.email.otp_greeting') }}
                            </p>

                            <p style="margin: 0 0 30px; color: #a1a1aa; font-size: 14px;">
                                {{ __('messages.email.otp_line1') }}
                            </p>

                            <!-- OTP Code -->
                            <div style="text-align: center; margin: 30px 0;">
                                <div style="display: inline-block; padding: 20px 40px; background: linear-gradient(135deg, rgba(168, 85, 247, 0.2), rgba(236, 72, 153, 0.1)); border: 1px solid rgba(168, 85, 247, 0.3); border-radius: 12px;">
                                    <span style="font-size: 36px; font-weight: bold; color: #ffffff; letter-spacing: 8px; font-family: monospace;">
                                        {{ $otpCode }}
                                    </span>
                                </div>
                            </div>

                            <p style="margin: 30px 0 0; color: #a1a1aa; font-size: 14px; text-align: center;">
                                {{ __('messages.email.otp_line2', ['minutes' => $expiresIn ?? 10]) }}
                            </p>

                            <hr style="margin: 30px 0; border: none; border-top: 1px solid rgba(168, 85, 247, 0.2);">

                            <p style="margin: 0; color: #71717a; font-size: 12px; text-align: center;">
                                {{ __('messages.email.otp_line3') }}
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 30px; background-color: #0f0f17; border: 1px solid rgba(168, 85, 247, 0.1); border-top: none; border-radius: 0 0 16px 16px; text-align: center;">
                            <p style="margin: 0 0 10px; color: #71717a; font-size: 12px;">
                                {{ __('messages.email.otp_thanks') }}<br>
                                {{ __('messages.email.otp_team') }}
                            </p>
                            <p style="margin: 0; color: #52525b; font-size: 11px;">
                                &copy; {{ date('Y') }} Collect Resayil - {{ __('messages.footer.rights') }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
