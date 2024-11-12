<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposal from {{ $data['app_name']->value }}</title>
    <style>
        /* Reset styling */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }

        /* Basic styling */
        body { margin: 0; padding: 0; width: 100%; height: 100%; font-family: Arial, sans-serif; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #717bff; padding: 20px; }
        .email-header { background-color: #000437; color: #ffffff; text-align: center; padding: 20px; }
        .email-header h1 { margin: 0; font-size: 24px; }
        .email-body { padding: 20px; font-size: 16px; color: #333333; }
        .email-body p { margin: 0 0 10px; line-height: 1.5; }
        .email-footer { text-align: center; font-size: 14px; color: #999999; padding: 20px; }
        .button { display: inline-block; padding: 10px 20px; background-color: #000437; color: #ffffff !important; text-decoration: none; border-radius: 5px; }

        /* Responsive styling */
        @media screen and (max-width: 600px) {
            .email-container { width: 100%; padding: 10px; }
            .email-header, .email-body, .email-footer { padding: 15px; }
        }
    </style>
</head>
<body>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <div class="email-container">
                    <!-- Header -->
                    <div class="email-header">
                        <h1>{{ $data['app_name']->value }}</h1>
                    </div>

                    <!-- Body -->
                    <div class="email-body">
                        <h2>Hello, {{ $data['proposal']->client->first_name.' '.$data['proposal']->client->last_name }}!</h2>
                        <p>You have a proposal, please view the proposal and approve it by signature</p>
                        <br/>
                        <p><a target="_blank" href="{{ route('users.proposal.show', $data['proposal']->slug)}}" class="button">Go to Proposal</a></p>
                    </div>

                    <!-- Footer -->
                    <div class="email-footer">
                        <p>&copy; {{ date('Y') }} <strong>{{ $data['app_name']->value }}.</strong> All rights reserved.</p>
                        {{-- <p>123 Your Street, City, State, 12345</p>
                        <p>If you no longer wish to receive these emails, <a href="#">unsubscribe here</a>.</p> --}}
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
