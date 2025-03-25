document.addEventListener('DOMContentLoaded', () => {
  // Navbar toggle functionality
  const navbarToggler = document.getElementById('navbarToggler')
  const navbarCollapse = document.getElementById('navbarNav')

  if (navbarToggler) {
    navbarToggler.addEventListener('click', () => {
      navbarCollapse.classList.toggle('show')
    })
  }

  // Auto-dismiss alerts after 5 seconds
  // const alerts = document.querySelectorAll(".alert")
  // alerts.forEach((alert) => {
  //   setTimeout(() => {
  //     alert.style.display = "none"
  //   }, 5000)
  // })

  // Toggle password visibility
  const togglePasswordButtons = document.querySelectorAll('.toggle-password')
  togglePasswordButtons.forEach((button) => {
    button.addEventListener('click', function () {
      const passwordField = document.querySelector(
        this.getAttribute('data-target')
      )
      const type =
        passwordField.getAttribute('type') === 'password' ? 'text' : 'password'
      passwordField.setAttribute('type', type)

      // Toggle icon
      this.querySelector('i').classList.toggle('fa-eye')
      this.querySelector('i').classList.toggle('fa-eye-slash')
    })
  })

  // Form validation for registration
  const registrationForm = document.getElementById('registrationForm')
  if (registrationForm) {
    registrationForm.addEventListener('submit', (event) => {
      let isValid = true

      // Validate password match
      const password = document.getElementById('password')
      const confirmPassword = document.getElementById('confirm_password')

      if (password.value !== confirmPassword.value) {
        confirmPassword.classList.add('is-invalid')
        isValid = false
      } else {
        confirmPassword.classList.remove('is-invalid')
      }

      // Validate O/L results
      const olResultSelects = document.querySelectorAll(
        'select[name^="ol_results"]'
      )
      let hasSelectedGrade = false

      olResultSelects.forEach((select) => {
        if (select.value) {
          hasSelectedGrade = true
        }
      })

      if (!hasSelectedGrade) {
        document.getElementById('ol-results-error').classList.remove('d-none')
        isValid = false
      } else {
        document.getElementById('ol-results-error').classList.add('d-none')
      }

      if (!isValid) {
        event.preventDefault()
      }
    })
  }

  // Form validation for application
  const applicationForm = document.getElementById('applicationForm')
  if (applicationForm) {
    applicationForm.addEventListener('submit', (event) => {
      let isValid = true

      // Validate stream selection
      const streamSelect = document.getElementById('stream_id')
      if (!streamSelect.value) {
        streamSelect.classList.add('is-invalid')
        isValid = false
      } else {
        streamSelect.classList.remove('is-invalid')
      }

      // Validate subject selection
      const subjectCheckboxes = document.querySelectorAll(
        'input[name="subjects[]"]:checked'
      )
      if (subjectCheckboxes.length === 0) {
        document.getElementById('subjects-error').classList.remove('d-none')
        isValid = false
      } else {
        document.getElementById('subjects-error').classList.add('d-none')
      }

      if (!isValid) {
        event.preventDefault()
      }
    })
  }

  // Confirm application status update
  const updateStatusForm = document.querySelector(
    'form[action*="updateStatus"]'
  )
  if (updateStatusForm) {
    updateStatusForm.addEventListener('submit', (event) => {
      const status = document.getElementById('status').value
      let message = ''

      if (status === 'approved') {
        message = 'Are you sure you want to approve this application?'
      } else if (status === 'rejected') {
        message = 'Are you sure you want to reject this application?'
      }

      if (!confirm(message)) {
        event.preventDefault()
      }
    })
  }
})
