import ROUTES from './components/routes.js'
import Utils from './components/utils.js'
import Auth from './components/auth.js'
import Notification from './components/notification.js'
import Loading from './components/loading.js'
import Dropdown from './components/dropdown.js'
import Articles from './articles/articles.js'
import Actions from './components/actions.js'

export default class App {

    Loading
    Notification
    Utils
    isLoggedIn = false

    constructor() {
        this.Loading = new Loading
        this.Notification = new Notification
        this.Utils = new Utils
        this.initializeAuth()
        new Dropdown
        // new Articles(this.Loading, this.Notification)
        // new Actions(this.Utils, this.Notification)
    }

    initializeAuth = () => {
        this.Utils.fetchJsonData(ROUTES.IS_LOGGED_IN)
            .then(data => {
                this.isLoggedIn = data.response
                if (this.isLoggedIn) {
                    localStorage.setItem('isLoggedIn', 'yes');
                }
                new Auth(this.Loading, this.Notification, this.Utils, this.isLoggedIn)
            })
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new App
})
