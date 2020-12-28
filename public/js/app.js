import ROUTES from './components/routes.js'
import Utils from './components/utils.js'
import Auth from './components/auth.js'
import Notification from './components/notification.js'
import Dropdown from './components/dropdown.js'
import Actions from './components/actions.js'
import Articles from './components/articles.js'
import Clickable from './components/clickable.js'

export default class App {

    Notification
    Utils
    isLoggedIn = false

    constructor() {
        this.Notification = new Notification
        this.Utils = new Utils
        this.initializeAuth()
        new Clickable
        new Dropdown
        new Articles(this.Utils, this.Notification)
    }

    initializeAuth = () => {
        this.Utils.fetchJsonData(ROUTES.IS_LOGGED_IN)
            .then(data => {
                this.isLoggedIn = data.response
                if (this.isLoggedIn) {
                    sessionStorage.setItem('isLoggedIn', 'yes');
                }
                new Auth(this.isLoggedIn, this.Notification, this.Utils)
                new Actions(this.isLoggedIn, this.Utils, this.Notification)
            })
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new App
})
