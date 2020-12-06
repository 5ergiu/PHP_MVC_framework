import Loading from './loading.js'
import ROUTES from './routes.js'

export default class Articles {

    #Utils
    #Notification
    #Loading
    #coverPreview
    #coverButton
    #removeCoverButton
    #coverFile
    #lastCoverFile = null
    #coverInput
    #title
    tags
    #markdown
    #oldMarkdown = null
    #articleCover
    #articleTitle
    articleTags
    #articleContent

    constructor(Utils, Notification) {
        this.#Utils = Utils
        this.#Notification = Notification
        this.#Loading = new Loading
        this.#coverPreview = document.getElementById('js-cover-preview')
        this.#coverButton = document.getElementById('js-button-cover')
        this.#removeCoverButton = document.getElementById('js-button-remove-cover')
        this.#coverFile = document.getElementById('js-cover-file')
        this.#coverInput = document.getElementById('js-cover-input')
        this.#title = document.getElementById('js-title')
        //tags
        this.#markdown = document.getElementById('js-markdown')
        this.#articleCover = document.getElementById('js-article-cover')
        this.#articleTitle = document.getElementById('js-article-title')
        //articletags
        this.#articleContent = document.getElementById('js-article-content')
        const articles = document.querySelectorAll('.article--mini')
        articles.forEach(element => {
            element.addEventListener('click', event => {
                this.handleClick(event.currentTarget)
            })
        })
        this.autoResize()
        this.retainState()
        this.handleChange()
        this.handleCoverChange()
        this.highlightCode()
    }

    // used to mimic clicks on 'mini' articles
    handleClick = (target) => {
        let isTextSelected = window.getSelection().toString()
        if (!isTextSelected) {
            target.querySelector('.js-main-article-link').click()
        }
    }

    // used to autoresize the article content(textarea) to have a much nicer appearance
    autoResize = () => {
        if (this.#markdown) {
            this.#markdown.addEventListener('input', () => {
                this.#markdown.style.height = this.#markdown.scrollHeight + 'px'
            })
        }
    }

    // used to highlight the <code> tags.
    highlightCode = () => {
        let elements = this.#articleContent.querySelectorAll('pre code')
        if (elements !== null) {
            elements.forEach(block => {
                hljs.highlightBlock(block);
            })
        }
    }

    // used to handle the change between 'edit' and 'preview' on articles/write
    handleChange = () => {
        const articleEdit = document.getElementById('js-edit')
        const buttonEdit = document.getElementById('js-button-edit')
        const articlePreview = document.getElementById('js-preview')
        const buttonPreview = document.getElementById('js-button-preview')
        if (buttonPreview && buttonEdit) {
            buttonPreview.addEventListener('click', () => {
                buttonPreview.classList.add('button--outline--active')
                buttonEdit.classList.remove('button--outline--active')
                articleEdit.classList.add('hide')
                articlePreview.classList.remove('hide')
                // to best serve the performance, we won't do the api call to the markdown parse on the markdown change, also
                // first we'll check if there were any modifications done to the markdown prior to making another api call
                if (this.#markdown.value && (this.#oldMarkdown === null || this.#markdown.value !== this.#oldMarkdown)) {
                    this.#oldMarkdown = this.#markdown.value
                    this.#Loading.show(document.getElementById('js-article-spinner'), articleEdit.parentNode)
                    let data = {
                        content: this.#markdown.value,
                    }
                    this.#Utils.fetchJsonData(ROUTES.PREVIEW, data)
                        .then(data => {
                            if (data.response) {
                                this.#articleContent.innerHTML = data.response
                                this.highlightCode()
                                this.#Loading.hide()
                            }
                        })
                }
            })
            buttonEdit.addEventListener('click', () => {
                buttonEdit.classList.add('button--outline--active')
                buttonPreview.classList.remove('button--outline--active')
                articleEdit.classList.remove('hide')
                articlePreview.classList.add('hide')
            })
        }
    }

    handleCoverChange = () => {
        if (this.#coverButton) {
            this.#coverButton.addEventListener('click', () => {
                this.#coverFile.click()
            })
            this.#coverFile.addEventListener('change', () => {
                this.#Loading.show(
                    document.getElementById('js-article-spinner'),
                    document.getElementById('js-edit')
                )
                let file = this.#coverFile.files
                if (file.length > 0) {
                    let data = new FormData
                    data.append('cover', file[0])
                    this.deleteLastCover()
                    this.upload(data)
                        .then(data => {
                            if (data.response) {
                                this.updateCoverPreview(data.response)
                                sessionStorage.setItem('cover', data.response);
                                this.#lastCoverFile = data.response
                            } else {
                                this.#Notification.show({
                                    message: 'Something went wrong, please try again',
                                    errors: true,
                                })
                            }
                        })
                }
                this.#Loading.hide()
            })
        }
        if (this.#removeCoverButton) {
            this.#removeCoverButton.addEventListener('click', this.deleteLastCover)
        }
    }

    upload = async data => {
        let response = await fetch(ROUTES.UPLOAD_COVER, {
            method: 'POST',
            body: data,
        })
        return await response.json()
    }

    updateCoverPreview = (filename) => {
        this.#coverInput.value = filename
        this.#coverPreview.classList.remove('hide')
        this.#coverPreview.style.backgroundImage = `url(/uploads/${filename})`
        this.#coverButton.innerText = 'Change cover'
        this.#removeCoverButton.classList.remove('hide')
        this.#articleCover.style.backgroundImage = `url(/uploads/${filename})`
        this.#articleCover.innerHTML = null
    }

    deleteLastCover = () => {
        if (this.#lastCoverFile !== null) {
            let data = {
                cover: this.#lastCoverFile
            }
            this.#Utils.fetchJsonData(ROUTES.DELETE_COVER, data)
                .then(data => {
                    if (data.response) {
                        sessionStorage.setItem('cover', '')
                        this.#lastCoverFile = null
                        this.#coverInput.value = null
                        this.#coverFile.value = null
                        this.#coverPreview.classList.add('hide')
                        this.#coverPreview.removeAttribute('style')
                        this.#articleCover.removeAttribute('style')
                        this.#articleCover.innerHTML = 'Your article\'s cover image'
                        this.#coverButton.innerText = 'Add a cover image'
                        this.#removeCoverButton.classList.add('hide')
                    } else {
                        this.#Notification.show({
                            message: data.errors.message,
                            errors: true,
                        })
                        return false
                    }
                })
        }
    }

    retainState = () => {
        let cover = sessionStorage.getItem('cover')
        if (cover) {
            this.updateCoverPreview(cover)
            this.#lastCoverFile = cover
        } else {
            this.#articleCover.innerHTML = 'Your article\'s cover image'
        }
        let title = sessionStorage.getItem('title')
        if (title) {
            this.#title.value = title
            this.#articleTitle.innerHTML = title
        } else {
            this.#articleTitle.innerHTML = 'Your article\'s title'
        }
        let markdown = sessionStorage.getItem('markdown')
        if (markdown) {
            this.#markdown.innerHTML = markdown
        } else {
            this.#articleContent.innerHTML = 'Your article\'s content'
        }
        this.#title.addEventListener('input', () => this.updateTitle())
        this.#markdown.addEventListener('input', () => this.updateContent())
    }

    updateTitle = () => {
        let title = this.#title.value.trim()
        sessionStorage.setItem('title', title)
        if (title) {
            this.#title.value = title
            this.#articleTitle.innerHTML = title
        } else {
            this.#articleTitle.innerHTML = 'Your article\'s title'
        }
    }

    updateContent = () => {
        let markdown = this.#markdown.value.trim()
        sessionStorage.setItem('markdown', markdown)
        if (markdown) {
            this.#markdown.innerHTML = markdown
        } else {
            this.#articleContent.innerHTML = 'Your article\'s content'
        }
    }
}
