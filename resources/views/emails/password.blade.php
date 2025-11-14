<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Your Account Credentials</title>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;800&display=swap" rel="stylesheet">
        <style>
            body,
            html {
                margin: 0;
                padding: 0;
                font-family: 'Manrope', sans-serif;
                background-color: #0c2461;
                /* Dark navy background */
            }

            .email-wrapper {
                padding: 40px 20px;
            }

            .email-container {
                max-width: 520px;
                margin: 0 auto;
                background-color: #1443e0;
                padding: 35px;
                border-radius: 12px;
                box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
                text-align: center;
            }

            .logo-container .snaboitz-logo {
                display: block;
                margin-left: auto;
                margin-right: auto;
                max-width: 140px;
                margin-bottom: 25px;
                /* Add space below the main logo */
            }

            .icon-container {
                margin-bottom: 20px;
            }

            .icon-container svg {
                /* Updated from img to svg */
                width: 50px;
                height: 50px;
            }

            h1 {
                color: #ffffff;
                font-size: 24px;
                font-weight: 800;
                margin: 0 0 10px 0;
            }

            p {
                font-size: 15px;
                color: #dbe4ff;
                line-height: 1.6;
                max-width: 400px;
                margin: 0 auto 20px auto;
            }

            .password-box {
                background-color: #0f35b0;
                border: 1px solid #2a5de8;
                padding: 15px;
                text-align: center;
                border-radius: 6px;
                font-size: 20px;
                font-weight: 700;
                color: #ffffff;
                letter-spacing: 3px;
                margin: 20px 0;
                font-family: 'Courier New', monospace;
            }

            .security-notice {
                font-size: 14px;
                margin-top: 20px;
            }

            .cta-btn {
                display: inline-block;
                padding: 14px 40px;
                background-color: #ffffff;
                color: #1443e0 !important;
                text-decoration: none;
                font-size: 16px;
                font-weight: 600;
                border-radius: 6px;
                margin-top: 20px;
            }

            .email-footer {
                font-size: 12px;
                color: #a0aec0;
                margin-top: 30px;
                text-align: center;
            }
        </style>
    </head>

    <body>
        <div class="email-wrapper">
            <div class="email-container">
                <div class="logo-container">
                    <img src="{{ asset('images/snaboitiz_logo_white.png') }}" alt="Snaboitz Logo" class="snaboitz-logo">
                </div>

                <div class="icon-container">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z" />
                        <circle cx="16.5" cy="7.5" r=".5" fill="white" />
                    </svg>
                </div>

                <h1>Your Account is Ready</h1>
                <p>Your account has been created. Please use the temporary password below to log in.</p>
                <div class="password-box">{{ $password }}</div>

                <p class="security-notice"><strong>This is a temporary password.</strong> You will be required to set a
                    new one after your first login to secure your account.</p>

                <a href="{{ config('app.url') }}" class="cta-btn">Log In</a>
            </div>
            <div class="email-footer">
                <p>&copy; {{ date('Y') }} SNAPp. All rights reserved.</p>
            </div>
        </div>
    </body>

</html>
