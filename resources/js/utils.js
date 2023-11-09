export const moneyFormat = (dollars) => {
  return `${dollars.toLocaleString()}.00`
}

export const currencyFormat = (dollars) => {
  return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
    })
    .format(dollars)
}

export const isValidEmail = (email) => {
  return String(email).toLowerCase().match(
    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
  )
}

export const getBrandClass = (brand) => {
  let icon = ''
  brand = brand || 'unknown'
  let cardBrandToClass = {
    'visa': 'fab fa-cc-visa',
    'mastercard': 'fab fa-cc-mastercard',
    'amex': 'fab fa-cc-amex',
    'discover': 'fab fa-cc-discover',
    'diners': 'fab fa-cc-diners-club',
    'jcb': 'fab fa-cc-jcb',
    'unknown': 'fa fa-credit-card',
  }
  if (cardBrandToClass[brand]) {
    icon = cardBrandToClass[brand]
  }

  return icon
}