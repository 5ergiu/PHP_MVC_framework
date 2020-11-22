export default class Utils {

    #formData = null;

    fetchJsonData = async (url, formData = this.#formData, method = 'POST') => {
        let response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        return await response.json()
    }

    serializeForm = form => {
        let serializedData = {}
        let formData = new FormData(form)
        for (let key of formData.keys()) {
            serializedData[key] = formData.get(key)
        }
        this.#formData = serializedData
        return this
    }

    readCookie = (name) => {
        let cookies = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)')
        return cookies ? cookies.pop() : ''
    }
}
