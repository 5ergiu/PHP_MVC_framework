export default class Notification {
    constructor() {
        this.notification = document.getElementById('js-notification')
        this.notificationIcon = document.getElementById('js-notification-icon')
        this.notificationMessage = document.getElementById('js-notification-message')
        if (this.notificationMessage.innerText !== '') {
            this.show()
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
    show = (options = null) => {
        if (options) {
            this.notificationMessage.innerHTML = options.message
            this.notificationIcon.src = options.imgPath
        }
        // we first check to see if the show class has already been added so we won't
        // have any weird animations
        if (!this.notification.classList.contains('notification--show')) {
            this.notification.className  = 'notification notification--show'
            // this.notification.animate([
            //         {
            //             top: '100%',
            //             opacity: 0,
            //         },
            //         {
            //             top: '90%',
            //             opacity: 1,
            //         },
            //     ], {
            //         duration: 300,
            //     },
            // )
            setTimeout(() => {
                // this.notification.animate([
                //         {top: '90%'},
                //         {top: '100%'},
                //     ], {
                //         duration: 300,
                //     },
                // )
                this.notification.className = 'notification notification--hide'
            }, 3000)
        }
    }
}
