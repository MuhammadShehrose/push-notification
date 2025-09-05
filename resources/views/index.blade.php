<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Push Notification Test</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Firebase compat SDKs -->
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging-compat.js"></script>

    <!-- OneSignal SDK -->
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
        <h1 class="mb-4">Push Notification Test</h1>
        <button id="get-firebase-token" class="btn btn-primary me-2">Get Firebase Token</button>
        <button id="get-onesignal-id" class="btn btn-dark">Get OneSignal Player ID</button>
    </div>

    <div id="toast-container" class="toast-container position-fixed"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /**
         * Helper: Show Bootstrap Toast
         */
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
        </div>`;
            const toastEl = wrapper.firstElementChild;
            container.appendChild(toastEl);
            const toast = new bootstrap.Toast(toastEl, {
                delay: 5000
            });
            toast.show();
            toastEl.addEventListener("hidden.bs.toast", () => toastEl.remove());
        }
    </script>

    <script>
        /**
         * Firebase Setup
         */
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

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        // Foreground push handler
        messaging.onMessage((payload) => {
            if (payload.notification) {
                showToast(payload.notification.title, payload.notification.body);
            }
        });

        // Button → Get Firebase token
        document.getElementById("get-firebase-token").addEventListener("click", () => {
            Notification.requestPermission().then((permission) => {
                if (permission === "granted") {
                    messaging.getToken({
                            vapidKey: VAPID_PUBLIC_KEY
                        })
                        .then((token) => {
                            console.log("✅ Firebase Token:", token);
                            alert("Firebase Device Token:\n" + token);
                        })
                        .catch((err) => console.error("❌ Error retrieving Firebase token:", err));
                } else {
                    console.warn("Notifications permission not granted.");
                }
            });
        });
    </script>

    <script>
        /**
         * OneSignal Setup
         */
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            await OneSignal.init({
                appId: "<?= env('ONESIGNAL_APP_ID') ?>",
                safari_web_id: "<?= env('ONESIGNAL_SAFARI_WEB_ID') ?>", // optional
                notifyButton: {
                    enable: true
                },
                allowLocalhostAsSecureOrigin: true,
                notifyOptions: {
                    showForegroundNotifications: false
                } // disable default popup
            });

            // Button → Get OneSignal Player ID
            document.getElementById("get-onesignal-id").addEventListener("click", async () => {
                try {
                    const id = await OneSignal.User.PushSubscription.id;
                    if (id) {
                        console.log("✅ OneSignal Player ID:", id);
                        alert("OneSignal Player ID:\n" + id);
                    } else {
                        console.warn("⚠️ No OneSignal Player ID (user not subscribed).");
                    }
                } catch (error) {
                    console.error("❌ Error getting OneSignal ID:", error);
                }
            });

            // Foreground handler → Show Bootstrap toast instead of default popup
            OneSignal.Notifications.addEventListener("foregroundWillDisplay", (event) => {
                // event.preventDefault(); // stop default popup
                // console.log("📩 OneSignal notification received:", event);

                showToast(
                    event.notification.title || "Notification",
                    event.notification.body || "You have a new message"
                );
            });

            OneSignal.Notifications.addEventListener("click", (event) => {
                console.log("🔔 OneSignal notification clicked:", event);
            });
        });
    </script>
</body>

</html>
