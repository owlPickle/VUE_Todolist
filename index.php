<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>TodoList</title>
  <link rel="stylesheet" href="node_modules/bulma/css/bulma.min.css" />
  <link rel="stylesheet" href="css/main.css" />
</head>

<body>
  <div id="app">
  </div>
  <script src="js/vue.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qs/6.7.0/qs.min.js"></script>
  <script>
    Vue.component('todo-form', {
      data() {
        return {
          content: '',
        }
      },
      methods: {
        submit() {
          this.$emit('input', this.content);
          this.content = '';
        },

      },
      template: `
      <form @submit.prevent="submit">
        <input v-model="content" type="text" placeholder="Write something" />
        <button type="submit">Add Todo</button>
      </form>
      `
    });

    Vue.component('list', {
      props: ['label'],
      methods: {
        remove() {
          this.$emit('remove', this.label)
        },
      },
      template: `
      <li>
        {{label.content}}
        <span @click="remove" class="button">x</span>  
      </li>
      `
    });

    new Vue({
      el: '#app',
      data: {
        todos: [],
      },
      mounted(){
        axios.get("todo/data.php")
        .then(res => {
          res.data.forEach(list => {
            this.todos.push(list);
          });
        });
      },
      methods: {
        addTodo(content) {
          let data = Qs.stringify({
            content: content,
          });
          axios.post('todo/create.php',data)
          .then(res => {
            const todoObj = {
              "id": res.data.id,
              "content": content,
            }
          
            this.todos.push(todoObj);
          })
        },
        trash(content) {
          let x = this.todos.indexOf(content);
          this.todos.splice(x, 1);

          let id = Qs.stringify({
            id: content.id,
          });
          axios.post('todo/delete.php', id)
        }
      },
      template: `
      <div class="container">
        <h1>TodoList</h1>
        <div>
          <todo-form @input="addTodo"></todo-form>
        </div>

        <div class="list">
          <ul>
            <list class="list-item" v-for="todo in todos" :label="todo" @remove="trash"></list>
          </ul>
        </div>
      </div>    
      `
    })
  </script>
</body>

</html>