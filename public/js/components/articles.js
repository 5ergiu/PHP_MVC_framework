import ROUTES from './routes.js'
/**
 This class will deal with all 'articles' related stuff.
 OBSERVATIONS REGARDING prepareUpload and removeImage!!! :
     Resetting lastImage and articleCoverInput so the user can
   select the same image they previously uploaded(just in case)
   * articleCoverInput is triggered only 'onChange'
   * images can be manually removed or removed when they're changed
     Also doing it like this won't let the user select the same image
   if it's already uploaded :)
 @TODO: This still has to be improved because we're loosing the data if the user
 were to refresh the page -> perhaps store this in the session or somethind to
 still be able to keep track of things even in case of a refresh.
 */
export default class Articles {

    #lastImage = null

    constructor(Loading, Notification) {
        this.Loading = Loading
        this.Notification = Notification
        this.editButton = document.getElementById('articleEditButton')
        this.previewButton = document.getElementById('articlePreviewButton')
        this.coverButton = document.getElementById('articleCoverButton')
        this.removeCoverButton = document.getElementById('articleRemoveCoverButton')
        this.articleCoverInput = document.getElementById('articleCoverInput')
        this.articleCoverFilename = document.getElementById('articleCoverFilename')
        this.editPost = document.querySelector('.article-write__edit')
        this.previewPost = document.querySelector('.article-write__preview')
        this.postCoverMiniPreview = document.querySelector('.article-write__cover--mini-preview')
        this.draftButton = document.getElementById('articleDraft')
        this.form = document.getElementById('articleForm')
        this.formData = new FormData
        if (this.coverButton !== null) {
            this.prepareUpload()
        }
        if (this.removeCoverButton !== null) {
            this.removeImage()
        }
        this.switchViews()
    }

    switchViews = () => {
        if (this.previewButton !== null && this.editButton !== null) {
            this.previewButton.addEventListener('click', () => {
                this.previewPost.classList.remove('hide')
                this.previewButton.classList.add('button--outline--active')
                this.editPost.classList.add('hide')
                this.editButton.classList.remove('button--outline--active')

            })
            this.editButton.addEventListener('click', () => {
                this.editPost.classList.remove('hide')
                this.editButton.classList.add('button--outline--active')
                this.previewPost.classList.add('hide')
                this.previewButton.classList.remove('button--outline--active')
            })
        }
    }

    prepareUpload = () => {
        this.coverButton.addEventListener('click', () => {
            this.articleCoverInput.click()
        })
        this.articleCoverInput.addEventListener('change', () => {
            let file = this.articleCoverInput.files
            if (file.length > 0) {
                this.formData.append('image', file[0])
                this.Loading.show()
                if (this.#lastImage !== null) {
                    this.deleteLastImage(this.#lastImage)
                        .then(response => {
                            if (response.result) {
                                this.#lastImage = null
                                this.articleCoverInput.value = null
                            } else {
                                this.Notification.show({
                                    isPrompt: true,
                                    message: response.message,
                                    imgPath: ROUTES.ERROR_IMAGE,
                                })
                            }
                        })
                }
                this.upload().then(response => {
                    this.Loading.hide()
                    if (response.result) {
                        this.Notification.show({
                            isPrompt: true,
                            message: response.message,
                            imgPath: ROUTES.CHECK_IMAGE,
                        })
                        this.#lastImage = response.image
                        this.articleCoverFilename.value = this.#lastImage
                        this.postCoverMiniPreview.classList.remove('hide')
                        this.postCoverMiniPreview.style.
                            backgroundImage = `url(../assets/uploads/${response.image})`
                        this.coverButton.innerText = 'Change cover'
                        this.removeCoverButton.classList.remove('hide')
                    } else {
                        this.Notification.show({
                            isPrompt: true,
                            message: response.message,
                            imgPath: ROUTES.ERROR_IMAGE,
                        })
                    }
                })
            }
        })
    }

    upload = async () => {
        let response = await fetch(ROUTES.UPLOAD_IMAGE, {
            method: 'POST',
            body: this.formData,
        })
        return await response.json()
    }

    deleteLastImage = async (file) => {
        let response = await fetch(ROUTES.DELETE_IMAGE, {
            method: 'POST',
            body: JSON.stringify(file),
        })
        return await response.json()
    }

    removeImage = () => {
        this.removeCoverButton.addEventListener('click', () => {
            this.deleteLastImage(this.#lastImage)
                .then(response => {
                    if (response.result) {
                        this.#lastImage = null
                        this.articleCoverInput.value = null
                        this.articleCoverFilename.value = this.#lastImage
                        this.postCoverMiniPreview.classList.add('hide')
                        this.postCoverMiniPreview.removeAttribute('style')
                        this.coverButton.innerText = 'Add a cover image'
                        this.removeCoverButton.classList.add('hide')
                    } else {
                        this.Notification.show({
                            isPrompt: true,
                            message: response.message,
                            imgPath: ROUTES.ERROR_IMAGE,
                        })
                    }
                })
        })
    }
}
