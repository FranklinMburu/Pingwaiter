let audioEnabled = localStorage.getItem('audioEnabled') === 'true' ? true : false;

function playNotificationSound() {
    const audio = new Audio("/assets/audio/service-bell.mp3");
    audio.volume = 0.2;
    audio.play().catch((e) => console.error("Sound playback failed:", e));
}

function waitForEchoAndBind() {
    if (typeof window.Echo === "undefined") {
        setTimeout(waitForEchoAndBind, 100);
        return;
    }

    const userMeta = document.querySelector('meta[name="restaurant-id"]');
    if (!userMeta) return;

    const restaurantId = userMeta.getAttribute("content");

    window.Echo.private("restaurant." + restaurantId)
        .listen(".ping.waiter", (data) => {
            if (audioEnabled) playNotificationSound();
        })
        .listen(".pending.order", (data) => {
            const code = data.order.table_code;
            const message = `Order from table ${code} is pending approval.`;

            if (audioEnabled) {
                const utterance = new SpeechSynthesisUtterance(message);
                speechSynthesis.speak(utterance);
            }

            addNotification(message, new Date().toLocaleTimeString());
        });

    window.Echo.connector.pusher.connection.bind("connected", () => {
        console.log(
            "%c✅ Pusher connected!",
            "color: green; font-weight: bold;"
        );
    });

    window.Echo.connector.pusher.connection.bind("error", (err) => {
        console.error("❌ Pusher connection error:", err);
    });
}

function addNotification(message, time) {
    const container = document.getElementById("notificationList");
    if (!container) return;

    const html = `
        <div class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-dark-icon">
            <div class="flex-shrink-0">
                <img class="size-10 rounded-50" src="/assets/images/user/profile-img.png" alt="user">
            </div>
            <div class="w-full ps-3">
                <div class="text-gray-500 dark:text-gray-400 text-sm mb-1.5">${message}</div>
                <div class="text-xs text-blue-600 dark:text-blue-500">${time}</div>
            </div>
        </div>`;
    container.insertAdjacentHTML("afterbegin", html);

    // Update badge count
    const badge = document.querySelector(
        '[data-popover-target="dropdownNotification"] span'
    );
    if (badge) badge.innerText = parseInt(badge.innerText || 0) + 1;
}

window.toggleGlobalSound = function () {
    audioEnabled = !audioEnabled;
    localStorage.setItem('audioEnabled', audioEnabled);

    // Update button appearance
    const soundToggle = document.getElementById("soundToggle");
    if (soundToggle) {
        if (audioEnabled) {
            soundToggle.innerHTML = '<i class="bi bi-volume-up-fill"></i> Sound On';
            soundToggle.classList.remove("sound-off");
            soundToggle.classList.add("sound-on");
        } else {
            soundToggle.innerHTML = '<i class="bi bi-volume-mute-fill"></i> Sound Off';
            soundToggle.classList.remove("sound-on");
            soundToggle.classList.add("sound-off");
        }
    }

    // Show SweetAlert2 notification
    Swal.fire({
        icon: audioEnabled ? "success" : "info",
        title: audioEnabled ? "Sound Enabled" : "Sound Muted",
        text: audioEnabled ? "Notifications will now play sound." : "Notifications will be silent.",
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
};

// Initialize button state on page load
document.addEventListener('DOMContentLoaded', () => {
    const soundToggle = document.getElementById("soundToggle");
    if (soundToggle) {
        if (audioEnabled) {
            soundToggle.innerHTML = '<i class="bi bi-volume-up-fill"></i> Sound On';
            soundToggle.classList.remove("sound-off");
            soundToggle.classList.add("sound-on");
        } else {
            soundToggle.innerHTML = '<i class="bi bi-volume-mute-fill"></i> Sound Off';
            soundToggle.classList.remove("sound-on");
            soundToggle.classList.add("sound-off");
        }
    }
});

waitForEchoAndBind();
