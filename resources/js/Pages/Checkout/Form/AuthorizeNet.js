export default class AuthorizeNet {
  authData = {
    clientKey: null,
    apiLoginID: null,
  }

  constructor (clientKey, apiLoginID) {
    this.authData.clientKey = clientKey
    this.authData.apiLoginID = apiLoginID
  }

  submitPayment(payload) {
    return new Promise((resolve, reject) => {
      window.Accept.dispatchData({ ...payload, authData: this.authData }, (response) => {
        if (response.messages.resultCode === 'Error') {
          reject(response)
          return
        }

        resolve(response)
      })
    })
  }
}