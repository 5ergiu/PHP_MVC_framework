import ROUTES from './routes.js'
import Loading from './loading.js'

export default class Actions {

    #isLoggedIn
    #Notification
    #Utils
    #userBookmarksCounter
    #bookmarksCounter
    #likesCounter

    constructor(isLoggedIn, Utils, Notification) {
        this.#isLoggedIn = isLoggedIn
        this.#Utils = Utils
        this.#Notification = Notification
        this.#userBookmarksCounter = document.getElementById('js-user-bookmarks-count')
        this.#bookmarksCounter = document.getElementById('js-bookmarks-count')
        this.#likesCounter = document.getElementById('js-likes-count')
        const bookmarkButtons = document.querySelectorAll('.js-button-bookmark')
        const likeButtons = document.querySelectorAll('.js-button-like')
        if (bookmarkButtons) {
            this.addEventListener(bookmarkButtons, this.bookmark)
        }
        if (likeButtons) {
            this.addEventListener(likeButtons, this.like)
        }
    }

    addEventListener = (elements, callback) => {
        elements.forEach(element => {
            element.addEventListener('click', () => {
                if (!this.#isLoggedIn) {
                    this.#Notification.show({
                        message: 'Please login first',
                        errors: true,
                    })
                } else {
                    callback(element)
                }
            })
        })
    }

    updateCounter = (counter, method) => {
        if (counter !== null) {
            if (method === 'add') {
                counter.innerHTML = parseInt(counter.innerHTML) + 1
            } else if (method === 'subtract') {
                let count = parseInt(counter.innerHTML)
                if (count > 0) {
                    counter.innerHTML = count - 1
                }
            }
        }
    }

    bookmark = (element) => {
        let loading = new Loading
        let spinner = element.parentNode.querySelector('.js-bookmark-spinner')
        loading.show(spinner, element)
        let data = {
            article_id: element.dataset.articleId,
        }
        element.disabled = true
        if (element.classList.contains('button--bookmarked')) {
            this.#Utils.fetchJsonData(ROUTES.BOOKMARK_REMOVE, data)
                .then(data => {
                    if (data.response) {
                        element.setAttribute('title', 'Bookmark article')
                        element.classList.remove('button--bookmarked')
                        element.innerHTML = '<i class="far fa-bookmark"></i>'
                        this.updateCounter(this.#userBookmarksCounter, 'subtract')
                        this.updateCounter(this.#bookmarksCounter, 'subtract')
                    } else {
                        this.#Notification.show({
                            message: data.errors,
                            errors: true,
                        })
                    }
                    element.disabled = false
                    loading.hide()
                })
        } else {
            this.#Utils.fetchJsonData(ROUTES.BOOKMARK_ADD, data)
                .then(data => {
                    if (data.response) {
                        element.setAttribute('title', 'Remove bookmark')
                        element.classList.add('button--bookmarked')
                        element.innerHTML = '📚'
                        this.updateCounter(this.#userBookmarksCounter, 'add')
                        this.updateCounter(this.#bookmarksCounter, 'add')
                    } else {
                        this.#Notification.show({
                            message: data.errors,
                            errors: true,
                        })
                    }
                    element.disabled = false
                    loading.hide()
                })
        }
    }

    like = (element) => {
        let loading = new Loading
        let spinner = element.parentNode.querySelector('.js-like-spinner')
        loading.show(spinner, element)
        let data = {
            article_id: element.dataset.articleId,
        }
        element.disabled = true
        if (element.classList.contains('button--liked')) {
            this.#Utils.fetchJsonData(ROUTES.LIKE_REMOVE, data)
                .then(data => {
                    if (data.response) {
                        element.setAttribute('title', 'Like article')
                        element.classList.remove('button--liked')
                        element.innerHTML = '<i class="far fa-heart"></i>'
                        this.updateCounter(this.#likesCounter, 'subtract')
                    } else {
                        this.#Notification.show({
                            message: data.errors,
                            errors: true,
                        })
                    }
                    element.disabled = false
                    loading.hide()
                })
        } else {
            this.#Utils.fetchJsonData(ROUTES.LIKE_ADD, data)
                .then(data => {
                    if (data.response) {
                        element.setAttribute('title', 'Unlike article')
                        element.classList.add('button--liked')
                        element.innerHTML = '❤️'
                        this.updateCounter(this.#likesCounter, 'add')
                    } else {
                        this.#Notification.show({
                            message: data.errors,
                            errors: true,
                        })
                    }
                    element.disabled = false
                    loading.hide()
                })
        }
    }
}
