<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt - {{ $donation->receipt_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            padding: 40px;
        }

        .receipt {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #059669;
            padding: 40px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #059669;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 5px;
        }

        .tagline {
            font-size: 14px;
            color: #666;
            font-style: italic;
        }

        .receipt-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 30px;
        }

        .receipt-number {
            text-align: right;
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }

        .row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dotted #e5e7eb;
        }

        .row:last-child {
            border-bottom: none;
        }

        .label {
            color: #666;
            font-weight: 500;
        }

        .value {
            color: #333;
            font-weight: 600;
            text-align: right;
        }

        .amount-box {
            background: #059669;
            color: white;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }

        .amount-label {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .amount-value {
            font-size: 36px;
            font-weight: bold;
            margin-top: 5px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        .thank-you {
            font-size: 18px;
            color: #059669;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .note {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 5px;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
        }

        @media print {
            body {
                padding: 0;
            }

            .receipt {
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="header">
            <div class="logo">🌟 {{ $foundation['name'] }}</div>
            <div class="tagline">{{ $foundation['tagline'] }}</div>
        </div>

        <div class="receipt-number">
            <strong>Receipt #:</strong> {{ $donation->receipt_number }}<br>
            <strong>Date:</strong> {{ $donation->created_at->format('F d, Y') }}
        </div>

        <div class="receipt-title">DONATION RECEIPT</div>

        <div class="amount-box">
            <div class="amount-label">Amount Received</div>
            <div class="amount-value">{{ $donation->formatted_amount }}</div>
        </div>

        <div class="section">
            <div class="section-title">Donor Information</div>
            <div class="row">
                <span class="label">Name</span>
                <span
                    class="value">{{ $donation->is_anonymous ? 'Anonymous Donor' : ($donation->donor_name ?? 'Not Provided') }}</span>
            </div>
            <div class="row">
                <span class="label">Email</span>
                <span class="value">{{ $donation->is_anonymous ? '***' : $donation->donor_email }}</span>
            </div>
            @if($donation->donor_phone && !$donation->is_anonymous)
                <div class="row">
                    <span class="label">Phone</span>
                    <span class="value">{{ $donation->donor_phone }}</span>
                </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Payment Details</div>
            <div class="row">
                <span class="label">Transaction ID</span>
                <span class="value">{{ $donation->transaction_id }}</span>
            </div>
            <div class="row">
                <span class="label">Payment Method</span>
                <span class="value">{{ ucfirst($donation->payment_gateway) }}</span>
            </div>
            <div class="row">
                <span class="label">Status</span>
                <span class="value" style="color: #059669;">{{ ucfirst($donation->status) }}</span>
            </div>
            @if($donation->is_recurring)
                <div class="row">
                    <span class="label">Donation Type</span>
                    <span class="value">Recurring ({{ ucfirst($donation->recurring_interval ?? 'Monthly') }})</span>
                </div>
            @endif
            @if($donation->campaign)
                <div class="row">
                    <span class="label">Campaign</span>
                    <span class="value">{{ $donation->campaign->title }}</span>
                </div>
            @endif
        </div>

        @if($donation->donor_message)
            <div class="section">
                <div class="section-title">Donor Message</div>
                <p style="color: #666; font-style: italic;">"{{ $donation->donor_message }}"</p>
            </div>
        @endif

        <div class="footer">
            <div class="thank-you">Thank you for your generous donation!</div>
            <p>Your contribution helps us continue our mission to support those in need.</p>
            <p style="margin-top: 10px;">
                For any queries, please contact us at hello@leofoundation.org
            </p>
        </div>

        <div class="note">
            <strong>Note:</strong> This is a computer-generated receipt and does not require a signature.
            Please retain this receipt for your records. For tax exemption purposes, please consult with your tax
            advisor.
        </div>
    </div>
</body>

</html>