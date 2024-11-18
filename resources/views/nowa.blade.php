<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">

        <title>Laravel</title>
        <script
            src="https://unpkg.com/vue@3/dist/vue.global.js">
        </script>

    </head>
    <body>
        <div>
            <h1>Bajo jajo</h1>
            <a href="/">welcome</a>
        </div>

        <div id="app">
            @verbatim
            {{ message }}
            @endverbatim
        </div>

        <div id="content">
            <li v-for="element in tablica">
                <p>
                    @verbatim
                    {{ element }}
                    @endverbatim
                </p>
            </li>
        </div>

        <script>
            const app = Vue.createApp({
                data() {
                    return {
                        message: "Hello World!"
                    }
                }
            })
            app.mount('#app')

            Vue.createApp({
                data() {
                    return {
                        tablica: ['jeden', 'dwa', 'trzy', 'czery']
                    };
                }
            }).mount('#content')
        </script>
    </body>
</html>
