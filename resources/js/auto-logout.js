/**
 * Auto Logout System with Activity Monitoring
 * Features:
 * - Monitors mouse, keyboard, touch, and scroll activity
 * - Shows warning modal before logout
 * - Extends session on activity
 * - Automatic logout after timeout
 */

class AutoLogout {
    constructor(options = {}) {
        this.warningTime = options.warningTime || 2 * 60 * 1000; // 2 minutes before logout
        this.logoutTime = options.logoutTime || 5 * 60 * 1000; // 5 minutes total inactivity
        this.checkInterval = options.checkInterval || 30000; // Check every 30 seconds
        this.activityEvents = [
            'mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click',
            'keydown', 'keyup', 'touchmove', 'wheel'
        ];

        this.lastActivity = Date.now();
        this.warningShown = false;
        this.logoutTimer = null;
        this.warningTimer = null;
        this.checkTimer = null;

        this.init();
    }

    init() {
        // Only initialize if user is authenticated
        if (!this.isAuthenticated()) return;

        this.bindEvents();
        this.startMonitoring();
        console.log('Auto logout system initialized');
    }

    isAuthenticated() {
        // Check if user is logged in (Laravel auth check)
        return typeof window.Laravel !== 'undefined' && window.Laravel.user;
    }

    bindEvents() {
        // Bind activity events
        this.activityEvents.forEach(event => {
            document.addEventListener(event, () => this.resetTimer(), { passive: true });
        });

        // Handle visibility change (tab switching)
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.resetTimer();
            }
        });
    }

    startMonitoring() {
        this.resetTimer();

        // Periodic check for server-side session
        this.checkTimer = setInterval(() => {
            this.checkServerSession();
        }, this.checkInterval);
    }

    resetTimer() {
        this.lastActivity = Date.now();

        // Clear existing timers
        if (this.logoutTimer) clearTimeout(this.logoutTimer);
        if (this.warningTimer) clearTimeout(this.warningTimer);

        // Hide warning if shown
        if (this.warningShown) {
            this.hideWarning();
        }

        // Set new timers
        this.warningTimer = setTimeout(() => {
            this.showWarning();
        }, this.logoutTime - this.warningTime);

        this.logoutTimer = setTimeout(() => {
            this.performLogout();
        }, this.logoutTime);
    }

    showWarning() {
        if (this.warningShown) return;

        this.warningShown = true;

        // Create warning modal
        const modal = document.createElement('div');
        modal.id = 'auto-logout-warning';
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle"></i> Peringatan Sesi
                        </h5>
                    </div>
                    <div class="modal-body">
                        <p>Sesi Anda akan berakhir dalam <span id="countdown" class="font-weight-bold">2:00</span> menit karena tidak ada aktivitas.</p>
                        <p>Klik "Lanjutkan Sesi" untuk tetap login, atau Anda akan otomatis logout.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='/logout'">
                            <i class="fas fa-sign-out-alt"></i> Logout Sekarang
                        </button>
                        <button type="button" class="btn btn-primary" id="extend-session">
                            <i class="fas fa-clock"></i> Lanjutkan Sesi
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        $('#auto-logout-warning').modal({
            backdrop: 'static',
            keyboard: false
        });

        // Start countdown
        this.startCountdown();

        // Bind extend session button
        document.getElementById('extend-session').addEventListener('click', () => {
            this.extendSession();
        });
    }

    hideWarning() {
        if (!this.warningShown) return;

        const modal = document.getElementById('auto-logout-warning');
        if (modal) {
            $('#auto-logout-warning').modal('hide');
            setTimeout(() => modal.remove(), 300);
        }
        this.warningShown = false;
    }

    startCountdown() {
        let timeLeft = this.warningTime / 1000; // Convert to seconds

        const countdownElement = document.getElementById('countdown');
        if (!countdownElement) return;

        const countdownInterval = setInterval(() => {
            timeLeft--;

            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                return;
            }

            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    }

    extendSession() {
        // Send request to extend session on server
        fetch('/extend-session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.resetTimer();
                this.showToast('Sesi berhasil diperpanjang', 'success');
            } else {
                this.showToast('Gagal memperpanjang sesi', 'error');
            }
        })
        .catch(error => {
            console.error('Error extending session:', error);
            this.showToast('Gagal memperpanjang sesi', 'error');
        });
    }

    performLogout() {
        this.showToast('Sesi berakhir karena tidak ada aktivitas', 'warning');
        setTimeout(() => {
            window.location.href = '/logout';
        }, 2000);
    }

    checkServerSession() {
        fetch('/check-session', {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(response => {
            if (response.status === 401) {
                // Session expired on server
                this.showToast('Sesi telah berakhir di server', 'warning');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error checking session:', error);
        });
    }

    showToast(message, type = 'info') {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'warning'} alert-dismissible position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            ${message}
        `;

        document.body.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                $(toast).fadeOut(() => toast.remove());
            }
        }, 5000);
    }

    destroy() {
        // Clean up timers and events
        if (this.logoutTimer) clearTimeout(this.logoutTimer);
        if (this.warningTimer) clearTimeout(this.warningTimer);
        if (this.checkTimer) clearInterval(this.checkTimer);

        this.activityEvents.forEach(event => {
            document.removeEventListener(event, this.resetTimer);
        });

        document.removeEventListener('visibilitychange', this.resetTimer);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add CSRF token meta tag if not exists
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const csrfMeta = document.createElement('meta');
        csrfMeta.name = 'csrf-token';
        csrfMeta.content = document.querySelector('input[name="_token"]')?.value || '';
        document.head.appendChild(csrfMeta);
    }

    // Initialize auto logout system
    window.autoLogout = new AutoLogout({
        warningTime: 2 * 60 * 1000, // 2 minutes warning
        logoutTime: 5 * 60 * 1000,  // 5 minutes total
        checkInterval: 30000         // Check every 30 seconds
    });
});

// Export for potential use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AutoLogout;
}
