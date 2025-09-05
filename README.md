<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

## 📌 About PushKit

**PushKit** is a Laravel-based push notification service that supports:

- ✅ **Firebase Cloud Messaging (FCM)**  
- ✅ **OneSignal**  
- ✅ **Email notifications**

It allows sending push notifications to **Web, Flutter, Angular, and mobile apps**, with flexible drivers controlled by environment variables.

---

## 🔧 Requirements

- PHP 8.1+  
- Laravel 10/11/12  
- Composer  
- Node.js (optional, for frontend testing)  

---

## 📦 Installation

```bash
git clone https://github.com/your-repo/pushkit.git
cd pushkit
composer install
php artisan key:generate
```

## ⚙️ Configuration

### 1. Firebase Setup

1. Go to [Firebase Console](https://console.firebase.google.com) and create a project.
2. Enable Cloud Messaging.
3. Download the `service-account.json`.
4. Place it at:
   ```
   storage/app/firebase/file.json
   ```
5. Add to `.env`:

```env
PUSH_DRIVER=fcm

FIREBASE_CREDENTIALS=storage/app/firebase/file.json
FIREBASE_API_KEY=your-api-key
FIREBASE_AUTH_DOMAIN=your-project.firebaseapp.com
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_STORAGE_BUCKET=your-project.appspot.com
FCM_SENDER_ID=your-sender-id
FIREBASE_APP_ID=your-app-id
FIREBASE_MEASUREMENT_ID=your-measurement-id
FIREBASE_VAPID_PUBLIC_KEY=your-vapid-public-key
```

### 2. OneSignal Setup

1. Go to [OneSignal Dashboard](https://onesignal.com).
2. Create a Web Push App.
3. Get your App ID and REST API Key.
4. (Optional) Enable Safari push and copy `safari_web_id`.
5. Add to `.env`:

```env
PUSH_DRIVER=onesignal

ONESIGNAL_APP_ID=your-onesignal-app-id
ONESIGNAL_API_KEY=your-onesignal-rest-api-key
ONESIGNAL_SAFARI_WEB_ID=your-optional-safari-id
```

### 3. Email Setup

Enable email notification fallback with `.env`:

```env
ENABLE_EMAIL=true

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="PushKit"
```

## 📂 File Placement

- **Firebase JSON** → `storage/app/firebase/file.json`
- **Firebase Service Worker** → `public/firebase-messaging-sw.js`
- **OneSignal Service Workers** →
  - `public/OneSignalSDKWorker.js`
  - `public/OneSignalSDKUpdaterWorker.js`

## 🔑 Environment Toggles

You can enable/disable services:

```env
ENABLE_PUSH_NOTIFICATION=true
ENABLE_EMAIL=false
```

## 🚀 Usage

### Example API Call (Postman)

**POST** `/api/push/send`

```json
{
  "tokens": [
    "firebase-device-token-or-onesignal-player-id"
  ],
  "title": "Hello World",
  "body": "This is a test push notification",
  "image": "https://example.com/image.png",
  "click_action": "https://your-site.test/",
  "email": [
    "user@example.com"
  ]
}
```

### Example Response

```json
{
  "ok": true,
  "message": "Push queued/sent",
  "report": { ... }
}
```

## 📨 Email Notifications

- Emails use the built-in `PushNotificationMail`.
- Run the queue worker to process them:

```bash
php artisan queue:work
```

## 🖥️ Testing Locally

Open in browser:
```
https://pushkit.test/
```

- **Firebase** → retrieves device token & shows Bootstrap toasts.
- **OneSignal** → retrieves Player ID & shows custom toasts in Chrome/Edge.

## 📋 Notes

- Firebase foreground notifications show Bootstrap toasts.
- OneSignal foreground notifications show custom toasts in Chromium browsers only.
- Safari/Firefox always show system notifications (cannot be prevented).
- Always refresh and store tokens in your DB for real-world apps.

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first.

## 🔒 Security

If you discover a security vulnerability, please report it privately.

## 📜 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
