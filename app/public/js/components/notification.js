/**
 * No matter how many actions the user would do, we should only show a single notification, so
 * the instance for this class will be passed from the main 'app.js' to whatever class needs it.
 */
export default class Notification {

    #notification
    #notificationIcon
    #notificationMessage

    constructor() {
        this.#notification = document.getElementById('js-notification')
        this.#notificationIcon = document.getElementById('js-notification-icon')
        this.#notificationMessage = document.getElementById('js-notification-message')
        this.show()
    }

    /**
     |--------------------------------------------------------------------------
     | Show method
     |--------------------------------------------------------------------------
     |
     | This will animate the notifications DOM element automatically if there's
     | a message present in 'notificationMessage' => this logic is for when the
     | backend handles the request and the notification is sent directly to the
     | view.
     | When an 'options' object will be passed, this means that the frontend is
     | handling the request, so some sort of API call was made so we're manually
     | calling this method('options.prompt' has to be true).
     |
     */
    show = (options = null) => {
        if (options) {
            this.#notificationMessage.innerHTML = options.message
            if (options.errors) {
                this.#notificationIcon.innerHTML = '❌'
            } else {
                this.#notificationIcon.innerHTML = '✔️'
            }
        }
        if (this.#notificationMessage.innerHTML.trim().length !== 0) {
            // we first check to see if the show class has already been added so we won't
            // have any weird animations
            if (!this.#notification.classList.contains('notification--show')) {
                this.#notification.className = 'notification notification--show'
                setTimeout(() => {
                    this.#notification.className = 'notification notification--hide'
                }, 3000)
            }
        }
    }
}
