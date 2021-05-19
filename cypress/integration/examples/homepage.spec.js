describe('Home page', () => {
    it('successfully loads', () => {
        cy.visit('/')
    })

})
describe('Enter username and password to log in', () => {
    it('logs in successfully', () => {
        cy.visit('/')
        cy.get('input[name=username]').type('jaana')
        cy.get('input[name=password]').type('kurimuri1234')
        cy.get('input[name=submit]').click()
    })
})
describe('Can not log in without entering username', () => {
    it('displays form validation', () =>{
        cy.visit('/')
        cy.get('input[name=username]').type('test').clear()
        cy.get('input[name=submit]').click()
        cy.get('#loginForm').within(() => {
            cy.get('#username').invoke('prop', 'validationMessage')
                .should('equal', 'Please fill out this field.')
        })
    })
})

describe('Can not log in without entering password', () => {
    it('displays form validation', () =>{
        cy.visit('/')
        cy.get('input[name=password]').type('test').clear()
        cy.get('input[name=submit]').click()
        cy.get('#loginForm').within(() => {
            cy.get('#password').invoke('prop', 'validationMessage')
                .should('equal', 'Please fill out this field.')
        })
    })
})

describe('Can not log in with correct username and wrong password', () => {
    it('displays error', () =>{
        cy.visit('/')
        cy.get('input[name=username]').type('jaana')
        cy.get('input[name=password]').type('test')
        cy.get('input[name=submit]').click()
        cy.get('.error_message').should('contain', 'Password or username incorrect.')
    })
})

describe('Can not log in with correct password and wrong username', () => {
    it('displays error', () =>{
        cy.visit('/')
        cy.get('input[name=username]').type('test')
        cy.get('input[name=password]').type('kurimuri1234')
        cy.get('input[name=submit]').click()
        cy.get('.error_message').should('contain', 'Password or username incorrect.')
    })
})
