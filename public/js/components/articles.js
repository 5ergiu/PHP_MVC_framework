import Loading from './loading.js'
import ROUTES from './routes.js'

export default class Articles {

    Utils
    #Loading
    #markdown
    #oldMarkdown = null
    #content

    constructor(Utils) {
        this.Utils = Utils
        this.#Loading = new Loading
        this.#markdown = document.getElementById('js-markdown')
        this.#content = document.getElementById('js-article-content')
        const articles = document.querySelectorAll('.article--mini')
        articles.forEach(element => {
            element.addEventListener('click', event => {
                this.handleClick(event.currentTarget)
            })
        })
        this.autoResize()
        this.handleChange()
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
        if (this.#content && this.#content.innerHTML.trim().length !== 0) {
            this.#content.querySelectorAll('pre code').forEach(block => {
                hljs.highlightBlock(block);
            });
        }
    }

    // used to handle the change between 'edit' and 'preview' on articles/write
    handleChange = () => {
        const articleEdit = document.getElementById('js-article-edit')
        const buttonEdit = document.getElementById('js-button-edit')
        const articlePreview = document.getElementById('js-article-preview')
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
                    this.Utils.fetchJsonData(ROUTES.PREVIEW, data)
                        .then(data => {
                            if (data.response) {
                                this.#content.innerHTML += data.response
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
}
