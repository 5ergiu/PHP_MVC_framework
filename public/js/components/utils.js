/**
 This class will contain all the useful methods that can be re-used
 throughout the project.
 */
export default class Utils {

    // method used to fetch data.
    fetchJsonData = async (url, formData, method = 'POST') => {
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

    // method used to serialize form data.
    serializeForm = (form) => {
        let serializedData = {}
        let formData = new FormData(form)
        for (let key of formData.keys()) {
            serializedData[key] = formData.get(key)
        }
        return serializedData
    }

    // method used to read cookies.
    readCookie = (name) => {
        let cookies = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)')
        return cookies ? cookies.pop() : ''
    }
}
