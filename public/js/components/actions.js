import ROUTES from './routes.js';

export default class Actions {

    #Loading
    #Notification
    #Utils
    #userBookmarksCounter
    #userBookmarksCount
    #bookmarksCounter
    #bookmarksCount
    #likesCounter
    #likesCount

    constructor(Loading, Utils, Notification) {
        this.#Loading = Loading
        this.#Utils = Utils
        this.#Notification = Notification
        this.#userBookmarksCounter = document.getElementById('js-user-bookmarks-count')
        this.#bookmarksCounter = document.getElementById('js-bookmarks-count')
        this.#likesCounter = document.getElementById('js-likes-count')
        this.setCounters()
        let bookmarkButtons = document.querySelectorAll('.js-button-bookmark')
        let likeButtons = document.querySelectorAll('.js-button-like')
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
                callback(element)
                this.updateCounters()
            })
        })
    }

    setCounters = () => {
        this.#userBookmarksCount = parseInt(this.#userBookmarksCounter?.innerHTML)
        this.#bookmarksCount = parseInt(this.#bookmarksCounter?.innerHTML)
        this.#likesCount = parseInt(this.#likesCounter?.innerHTML)
    }

    updateCounters = () => {
        this.#userBookmarksCounter.innerHTML = this.#userBookmarksCount
        this.#bookmarksCounter.innerHTML = this.#bookmarksCount
        this.#likesCounter.innerHTML = this.#likesCount
    }

    bookmark = (element) => {
        let spinner = element.parentNode.querySelector('.js-bookmark-spinner')
        this.#Loading.show(spinner, element)
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
                        if (this.#userBookmarksCount > 0) {
                            this.#userBookmarksCount = this.#userBookmarksCount - 1
                        }
                        if (this.#bookmarksCount > 0) {
                            this.#bookmarksCount = this.#bookmarksCount - 1
                        }
                    } else {
                        this.#Notification.show({
                            isPrompt: true,
                            message: data.errors,
                            imgPath: ROUTES.ERROR_IMAGE,
                        })
                    }
                })
        } else {
            this.#Utils.fetchJsonData(ROUTES.BOOKMARK_ADD, data)
                .then(data => {
                    if (data.response) {
                        element.setAttribute('title', 'Remove bookmark')
                        element.classList.add('button--bookmarked')
                        element.innerHTML = '📚'
                        this.#userBookmarksCount = this.#userBookmarksCount + 1
                        this.#bookmarksCount = this.#bookmarksCount + 1
                    } else {
                        this.#Notification.show({
                            isPrompt: true,
                            message: data.errors,
                            imgPath: ROUTES.ERROR_IMAGE,
                        })
                    }
                })
        }
        element.disabled = false
        this.#Loading.hide()
    }

    like = (element) => {
        let spinner = element.parentNode.querySelector('.js-like-spinner')
        this.#Loading.show(spinner, element)
        let data = {
            article_id: element.dataset.articleId,
        }
        element.disabled = true
        if (element.classList.contains('button--liked')) {
            this.#Utils.fetchJsonData(ROUTES.LIKE_ADD, data)
                .then(data => {
                    if (data.response) {
                        element.setAttribute('title', 'Like article')
                        element.classList.remove('button--liked')
                        element.innerHTML = '<i class="far fa-heart"></i>'
                        if (this.#likesCount > 0) {
                            this.#likesCount = this.#likesCount - 1
                        }
                    } else {
                        this.#Notification.show({
                            isPrompt: true,
                            message: data.errors,
                            imgPath: ROUTES.ERROR_IMAGE,
                        })
                    }
                })
        } else {
            this.#Utils.fetchJsonData(ROUTES.LIKE_REMOVE, data)
                .then(data => {
                    if (data.response) {
                        element.setAttribute('title', 'Unlike article')
                        element.classList.add('button--liked')
                        element.innerHTML = '📚'
                        this.#likesCount = this.#likesCount + 1
                    } else {
                        this.#Notification.show({
                            isPrompt: true,
                            message: data.errors,
                            imgPath: ROUTES.ERROR_IMAGE,
                        })
                    }
                })
        }
        element.disabled = false
        this.#Loading.hide()
    }
}
