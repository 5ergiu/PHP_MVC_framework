import Notification from './components/notification.js'
import Loading from './components/loading.js'
import Dropdown from './components/dropdown.js'
import Articles from './articles/articles.js'
import Actions from './components/actions.js'
import Utils from './components/utils.js';

/**
 This class will be instantiated on every request and will be used to
 instantiate all other classes + pass down certain 'helper' methods.
 */
class App {

    constructor() {
        // this.Loading = new Loading
        // this.Notification = new Notification
        this.Utils = new Utils
        // new Articles(this.Loading, this.Notification)
        new Dropdown
        // new Actions(this.Utils, this.Notification)
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new App
})
