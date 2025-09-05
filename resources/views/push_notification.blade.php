<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $data['title'] ?? 'Notification' }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,.1);">
        <h2 style="color: #2d3748;">{{ $data['title'] ?? 'Notification' }}</h2>
        <p style="font-size: 16px; color: #4a5568;">
            {{ $data['body'] ?? '' }}
        </p>

        @if(!empty($data['image']))
            <div style="margin: 15px 0;">
                <img src="{{ $data['image'] }}" alt="Notification Image" style="max-width: 100%; border-radius: 6px;">
            </div>
        @endif

        @if(!empty($data['click_action']))
            <p>
                <a href="{{ $data['click_action'] }}" style="background: #3182ce; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
                    View Details
                </a>
            </p>
        @endif
    </div>
</body>
</html>
