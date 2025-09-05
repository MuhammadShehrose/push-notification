<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Firebase Push Test</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Firebase compat SDKs (for service worker importScripts compatibility) -->
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging-compat.js"></script>

    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>

    <style>
        body {
            padding: 2rem;
        }

        #toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4">Firebase Push Notification Test</h1>
        <button id="get-token" class="btn btn-primary">Get Device Token</button>
    </div>

    <div id="toast-container" class="toast-container position-fixed"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Client Firebase config pulled from Laravel config
        const firebaseConfig = {!! json_encode([
            'apiKey' => config('firebase.api_key'),
            'authDomain' => config('firebase.auth_domain'),
            'projectId' => config('firebase.project_id'),
            'storageBucket' => config('firebase.storage_bucket'),
            'messagingSenderId' => config('firebase.messaging_sender_id'),
            'appId' => config('firebase.app_id'),
            'measurementId' => config('firebase.measurement_id'),
        ]) !!};

        const VAPID_PUBLIC_KEY = "{{ config('firebase.vapid_public_key') }}";

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);

        const messaging = firebase.messaging();

        function showToast(title, body) {
            const container = document.getElementById("toast-container");
            const wrapper = document.createElement("div");
            wrapper.innerHTML = `
        <div class="toast align-items-center text-bg-primary border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body">
              <strong>${title}</strong><br>${body}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      `;
            const toastEl = wrapper.firstElementChild;
            container.appendChild(toastEl);
            const toast = new bootstrap.Toast(toastEl, {
                delay: 5000
            });
            toast.show();
            toastEl.addEventListener("hidden.bs.toast", () => toastEl.remove());
        }

        // Foreground handler
        messaging.onMessage((payload) => {
            if (payload.notification) {
                showToast(payload.notification.title, payload.notification.body);
            }
        });

        document.getElementById("get-token").addEventListener("click", () => {
            Notification.requestPermission().then((permission) => {
                if (permission === "granted") {
                    messaging.getToken({
                            vapidKey: VAPID_PUBLIC_KEY
                        })
                        .then((token) => {
                            console.log("✅ Device token:", token);
                            alert("Device token:\n" + token);
                        })
                        .catch((err) => console.error("❌ Error retrieving token:", err));
                } else {
                    console.warn("Notifications permission not granted.");
                }
            });
        });
    </script>

    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            await OneSignal.init({
                appId: "0513bae4-d083-4d5d-ba62-6798b7fea943", // Your App ID from dashboard
                safari_web_id: "web.onesignal.auto.09714a24-a3bb-414f-8109-d75a4f07e6fa", // Only if Safari push enabled
                notifyButton: {
                    enable: true, // Shows a small bell button on the site
                },
            });
        });
    </script>
</body>

</html>
