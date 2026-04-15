import { ref } from 'vue'

// Replaces the NewsletterForm mixin from the 2020 theme.
export function useNewsletter(defaultMessage: string) {
  const email = ref('')
  const message = ref(defaultMessage)
  const processingRequest = ref(false)
  const showInput = ref(true)
  const buttonText = ref('Subscribe')

  async function subscribe() {
    if (processingRequest.value) return

    processingRequest.value = true
    buttonText.value = '. '

    // Animate the button text while the request is in flight
    const interval = setInterval(() => {
      buttonText.value += '. '
    }, 250)

    try {
      const api = useWpApi()
      const response = await api.subscribe(email.value)

      if (response.code === 200 || response.code === 202) {
        message.value = "Thanks! You're almost subscribed! Just check your e-mail for the confirmation link."
        showInput.value = false
      } else {
        message.value = 'Error subscribing! Check your e-mail or let Harrison know.'
        buttonText.value = 'Subscribe'
      }
    } catch {
      message.value = 'Error subscribing! Check your e-mail or let Harrison know.'
      buttonText.value = 'Subscribe'
    } finally {
      clearInterval(interval)
      processingRequest.value = false
    }
  }

  return { email, message, processingRequest, showInput, buttonText, subscribe }
}
