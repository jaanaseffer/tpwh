describe('Insert work hours', () => {

    describe('Enter username and password to log in', () => {
        it('logs in successfully', () => {
            cy.visit('/')
            cy.get('input[name=username]').type('jaana')
            cy.get('input[name=password]').type('kurimuri1234')
            cy.get('input[name=submit]').click()
        })

        it('successfully loads dashboard', () => {
            cy.visit('https://tpwh.seffer.ee/dashboard.php')
        })
    })

    describe('Fills in table row', () => {
        it('inserts work hours', () => {
            cy.visit('/')
            cy.get('input[name=username]').type('jaana')
            cy.get('input[name=password]').type('kurimuri1234')
            cy.get('input[name=submit]').click()

            cy.get('#add').click()
            cy.get('#coverage_1').type('42111')
            cy.get('#coverage_name_1').type('Bodo')
            cy.get('#project_help_1').type('13111')
            cy.get('#data6').within(() => {
                cy.get('select').select('CP').should('have.value', 'CP')
            })
            cy.get('#data7').type('8')
            cy.get('#data8').type('Finalization')
            cy.get('#insert').click()
            cy.get('#alert_message').should('contain', 'Work hours inserted!')
        })
    })
})
