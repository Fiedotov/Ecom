import { currencyFormat } from '@/utils'

export class SkuInfo {
  property
  paymentCount
  discount

  constructor (property, paymentCount) {
    this.property = property
    this.paymentCount = paymentCount
  }

  description () {
    return {
      1: `Pay In Full`,
      24: `Plan - ${this.paymentCount} months`,
      36: `Plan - ${this.paymentCount} months`,
      48: `Plan - ${this.paymentCount} months`,
      60: `Plan - ${this.paymentCount} months`,
      90: `Plan - ${this.paymentCount} months`,
      120: `Plan - ${this.paymentCount} months`,
    }[this.paymentCount]
  }

  paymentAmount () {
    return {
      1: null,
      [this.property.term_1]: this.property.payment_1,
      [this.property.term_2]: this.property.payment_2,
      [this.property.term_3]: this.property.payment_3,
    }[this.paymentCount]
  }

  escrowAndFees() {
    return (
      this.property.annual_property_taxes +
      this.property.hoa_poa_annual_fee +
      this.property.annual_tax_hoa_amount_additional_fee
    ) / 12
  }

  recurringTotal() {
    let paymentAmount = this.paymentAmount()

    if (this.discount) {
       paymentAmount -= this.totalDiscount()
    }

    return paymentAmount + +this.escrowAndFees().toFixed(2);
  }

  subtotal () {
    return {
      1: this.property.cash_price_current,
      [this.property.term_1]: this.property.document_fee + this.property.down_payment,
      [this.property.term_2]: this.property.document_fee + this.property.down_payment,
      [this.property.term_3]: this.property.document_fee + this.property.down_payment,
    }[this.paymentCount]
  }

  items() {
    if (this.paymentCount === 1) {
      return [
        { label: 'Description', value: this.description() },
        { label: 'Subtotal', value: `${currencyFormat(this.subtotal())}` }
      ]
    }

    return [
      { label: 'Description', value: this.description() },
      { label: 'Property', value: `${currencyFormat(this.paymentAmount())}` },
      { label: 'Escrow and Fees', value: `${currencyFormat(this.escrowAndFees())}` },
    ]
  }
  
  setDiscount(discount) {
    this.discount = discount
  }

  totalDiscount() {
    if (!this.discount) {
      return 0
    }

    switch (this.discount.discount_strategy) {
      case 'fixed':
        return this.discount.discount_amount

      case 'percentage':
        if (this.paymentCount === 1) {
          return (this.discount.discount_amount / 100) * this.subtotal()
        }

        return (this.discount.discount_amount / 100) * this.paymentAmount()
    }
  }

  clearDiscount() {
    this.discount = null
  }
}