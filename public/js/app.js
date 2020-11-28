import ROUTES from './components/routes.js'
import Utils from './components/utils.js'
import Auth from './components/auth.js'
import Notification from './components/notification.js'
import Dropdown from './components/dropdown.js'
import Actions from './components/actions.js'
import Articles from './components/articles.js'
import Clickable from './components/clickable.js'

export default class App {

    Loading
    Notification
    Utils
    isLoggedIn = false

    constructor() {
        this.Notification = new Notification
        this.Utils = new Utils
        this.initializeAuth()
        new Dropdown
        new Clickable
        new Articles
        new Actions(this.Utils, this.Notification)
    }

    initializeAuth = () => {
        this.Utils.fetchJsonData(ROUTES.IS_LOGGED_IN)
            .then(data => {
                console.log(data)
                this.isLoggedIn = data.response
                if (this.isLoggedIn) {
                    localStorage.setItem('isLoggedIn', 'yes');
                }
                new Auth(this.Notification, this.Utils, this.isLoggedIn)
            })
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new App
})
