/**
 This class will be used to show notifications such as messages, errors, etc.
 */
export default class Notification {

    constructor() {
        this.notification = document.querySelector('.notification')
        this.notificationMessage = document.querySelector('.notification__message')
        this.notificationsImg = document.querySelector('.notification__image')
        if (this.notificationMessage !== null) {
            if (this.notificationMessage.innerText !== '') {
                this.show()
            }
        }
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
    show = (options = {}) => {
        if (options.isPrompt) {
            this.notificationMessage.innerHTML = options.message
            this.notificationsImg.src = options.imgPath
        }
        this.notification.classList.add('notification--show')
        this.notification.animate([
            {
                top: '100%',
                opacity: 0,
            },
            {
                top: '85%',
                opacity: 1,
            },
            ], {
            duration: 500,
            },
        )
        setTimeout(() => {
            this.notification.animate([
                {top: '85%'},
                {top: '100%'},
                ], {
                    duration: 300,
                },
            )
            this.notification.classList.remove('notification--show')
        }, 3000)
    }
}
