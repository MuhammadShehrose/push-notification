// import compat libraries for SW
importScripts("https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging-compat.js");

const firebaseConfig = {
  apiKey: "<?= getenv('FIREBASE_API_KEY') ?: '' ?>",
  authDomain: "<?= getenv('FIREBASE_AUTH_DOMAIN') ?: '' ?>",
  projectId: "<?= getenv('FIREBASE_PROJECT_ID') ?: '' ?>",
  storageBucket: "<?= getenv('FIREBASE_STORAGE_BUCKET') ?: '' ?>",
  messagingSenderId: "<?= getenv('FCM_SENDER_ID') ?: '' ?>",
  appId: "<?= getenv('FIREBASE_APP_ID') ?: '' ?>"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
  console.log("📩 Background push received:", payload);

  const title = payload.notification?.title || 'Notification';
  const options = {
    body: payload.notification?.body || '',
    icon: payload.notification?.icon || '/firebase-logo.png',
    data: payload.data || {}
  };

  self.registration.showNotification(title, options);
});

self.addEventListener('notificationclick', function (event) {
  event.notification.close();
  const clickUrl = event.notification.data?.click_action || '/';
  event.waitUntil(clients.openWindow(clickUrl));
});
