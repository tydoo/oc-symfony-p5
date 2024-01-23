export default {
    init: () => {
        document.documentElement.addEventListener('dsfr.scheme', (e) => {
            const html = document.querySelector('html')
            if (html.dataset.frTheme === 'dark') {
                html.classList.add('dark')
            } else {
                html.classList.remove('dark')
            }
        })
    }
}
