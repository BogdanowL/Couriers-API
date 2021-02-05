<template>
<div>
    <validation v-if="validationErrors"
                :errors="validationErrors"
    >
    </validation>
    <div v-if="errored" class="alert alert-danger text-center" role="alert">
       Запись не найдена, проверьте правильность ввода
    </div>
    <div class="col-md-12">
        <div class="row ">

                <div  class="col-sm-4">
                    <h2 v-if="edit === false">Добавить пользователя</h2>
                    <h2 v-else>Редактировать: {{ user.name + ' ' + user.surname }}</h2>
                    <form @submit.prevent="addUser()" class="mb-4">
                        <div class="form-group">
                            <label for="name">Имя</label>
                            <input v-model="user.name" type="text" class="form-control" id="name" >
                            <label for="surname">Фамилия</label>
                            <input v-model="user.surname" type="text" class="form-control" id="surname" >
                            <label for="email">Email</label>
                            <input v-model="user.email" type="text" class="form-control" id="email" >
                            <label for="phone">Телефон</label>
                            <input v-model="user.phone" type="text" class="form-control" id="phone" >
                            <div v-if="edit === false">
                                <label for="order"><strong>Заказ</strong></label>
                                <input v-model="user.order" type="text" class="form-control" id="order" >
                            </div>
                            <div v-else></div>

                        </div>
                        <button v-if="edit === false" type="submit" class="btn btn-primary">Добавить</button>
                        <button v-else type="submit" class="btn btn-success">Сохранить Изменения</button>
                    </form>
                </div>
            <div v-if="edit !== false" class="col-sm-6 offset-2">

                    <div class="form-group">

                        <div v-for="order in user.orders" :key="order.id" >
                            <form @submit.prevent="editOrder(order)" class="mb-2">
                            <label for="edit"><strong>Заказ № {{ order.id }} {{user.name + ' ' + user.phone}}</strong></label>
                            <input  v-model="order.order"  type="text" class="form-control" id="edit" >
                            <button type="submit" class="btn btn-success mt-3 mb-3">Изменить заказ</button>
                            <button @click="deleteOrder(order.id)" type="submit" class="btn btn-danger mt-3 mb-3 d-flex float-right">Удалить заказ</button>
                            </form>
                        </div>

                    </div>

                <hr>
                <form @submit.prevent="addOrder()" class="mb-2">
                    <div class="form-group">
                        <label for="addOrder"><strong>Добавить Заказ</strong></label>
                            <input v-model="user.order" type="text" class="form-control" id="addOrder" >
                        <button type="submit" class="btn btn-primary mt-3">Добавить Заказ</button>
                    </div>
                </form>
            </div>
            <div v-else></div>

        </div>
    </div>

    <div class="col-md-12">
        <h3 >Клиенты и заказы: {{search}}</h3>
        <form @submit.prevent="getSearch(search)" >
            <div class="form-row align-items-center">
                <div class="col-sm-3 my-1">
                    <label class="sr-only" for="search">Поиск</label>
                    <input v-model="search" placeholder="ID Имя Фамилия Телефон" type="text" class="form-control" id="search" >
                </div>

                <div class="col-auto my-1">
                    <button type="submit" class="btn btn-primary">Поиск</button>
                </div>
            </div>
        </form>


        <div class="row">
            <table class="table mt-5">
                        <div v-if="loading"><h2>Загружаем записи...</h2></div>
                        <thead>
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Имя</th>
                            <th scope="col">Фамилия</th>
                            <th scope="col">email</th>
                            <th scope="col">телефон</th>
                            <th scope="col">всего заказов</th>
                            <th scope="col">заказы</th>
                            <th scope="col">Операции</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="user in users" :key="user.id">
                            <th>{{ user.id }}</th>
                            <td>{{ user.name }}</td>
                            <td>{{ user.surname }}</td>
                            <td>{{ user.email }}</td>
                            <td>{{ user.phone }}</td>
                            <td> <strong>{{ user.orders.length }} </strong> </td>
                            <td v-if="user.orders.length === 0">нет заказов</td>
                            <td v-for="order in user.orders" :key="order.id" class="row">
                                <strong >{{ order.order }}</strong>
                            </td>
                            <td>
                                <div class="d-inline-block">
                                    <button @click="editUser(user)" class="btn mt-2 btn-success">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button @click="deleteUser(user.id)" class="btn mt-2 btn-danger">
                                        <i class="far fa-times-circle"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
            <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li :class="{ disabled: !pagination.prev_page_url }"
                                @click.prevent="getUsers(pagination.prev_page_url)"
                                class="page-item">
                                <a class="page-link" href="#">Назад</a>
                            </li>
                            <li class="page-item">
                                <a @click.prevent="getUsers(pagination.first_page_url)"  class="page-link" href="#">  1 </a>
                            </li>
                            <li class="page-item disabled">
                                <a class="page-link " href="#"> страница: {{ pagination.current_page }} из {{ pagination.last_page }}</a>
                            </li>
                            <li class="page-item">
                                <a  @click.prevent="getUsers(pagination.last_page_url)"
                                    class="page-link" href="#">{{ pagination.last_page }}</a>
                            </li>
                            <li :class="{ disabled: !pagination.next_page_url }"
                                @click.prevent="getUsers(pagination.next_page_url)"
                                class="page-item">
                                <a class="page-link" href="#">Следующая</a>
                            </li>
                        </ul>
                    </nav>

        </div>
    </div>

    <div class="col-md-12">
        <div class="row">
            <h1 class="mt-5">Заказы:</h1>

            <table class="table ">
                <thead>
                <tr>
                    <th scope="col">№ Заказа</th>
                    <th scope="col">Имя</th>
                    <th scope="col">Email</th>
                    <th scope="col">Заказ</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="order in orders" :key="order.id">
                    <th>{{ order.id }}</th>
                    <th>{{ order.user.name }}</th>
                    <th>{{ order.user.email }}</th>
                    <th>{{ order.order }}</th>
                </tr>
                </tbody>
            </table>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li :class="{ disabled: !ordersPagination.prev_page_url }"
                        @click.prevent="getOrders(ordersPagination.prev_page_url)"
                        class="page-item">
                        <a class="page-link" href="#">Назад</a>
                    </li>
                    <li class="page-item">
                        <a @click.prevent="getOrders(ordersPagination.first_page_url)"  class="page-link" href="#">  1 </a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link " href="#"> страница: {{ ordersPagination.current_page }} из {{ ordersPagination.last_page }}</a>
                    </li>
                    <li class="page-item">
                        <a  @click.prevent="getOrders(ordersPagination.last_page_url)"
                            class="page-link" href="#">{{ ordersPagination.last_page }}</a>
                    </li>
                    <li :class="{ disabled: !ordersPagination.next_page_url }"
                        @click.prevent="getOrders(ordersPagination.next_page_url)"
                        class="page-item">
                        <a class="page-link" href="#">Следующая</a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
            </div>






</template>

<script>
    export default {
        data() {
            return {
                users: [],
                orders: [],
                order: {
                    user_id: '',
                    order: ''
                },
                user: {
                    id: '',
                    name: '',
                    surname: '',
                    email: '',
                    phone: '',
                    orders: {}
                },
                pagination: {},
                ordersPagination: {},
                edit: false,
                loading: true,
                errored: false,
                search: '',
                validationErrors: ''
            }
        },
        mounted() {
            this.getUsers()
            this.getOrders()
        },
        methods: {
            getOrders(page_url) {
                page_url = page_url || '/api/orders'
                axios
                    .get(page_url)
                    .then(response => {
                        console.log(response.data.data)
                        this.orders = response.data.data
                        this.makeOrdersPagination(response.data)
                    })
                    .catch(error => {

                        console.log(error)
                        this.errored = true;
                    })
                    .finally(() => this.loading = false);
            },
            getUsers(page_url) {
                page_url = page_url || '/api/users'
                axios
                    .get(page_url)
                    .then(response => {
                        this.users = response.data.data
                        this.makePagination(response.data)
                    })
                    .catch(error => {

                        console.log(error)
                        this.errored = true;
                    })
                    .finally(() => this.loading = false);
            },
            makePagination(response) {
                let pagination = {
                    current_page: response.current_page,
                    last_page: response.last_page,
                    first_page_url: response.first_page_url,
                    last_page_url: response.last_page_url,
                    prev_page_url: response.prev_page_url,
                    next_page_url: response.next_page_url,
                    per_page: response.per_page
                }
                this.pagination = pagination
            },
            makeOrdersPagination(response){
                let ordersPagination = {
                    current_page: response.current_page,
                    last_page: response.last_page,
                    first_page_url: response.first_page_url,
                    last_page_url: response.last_page_url,
                    prev_page_url: response.prev_page_url,
                    next_page_url: response.next_page_url,
                    per_page: response.per_page
                }
                this.ordersPagination = ordersPagination
            },
            addUser() {
                if (this.edit === false)
                {
                    //добавление
                    axios
                        .post('api/users', {
                            name: this.user.name,
                            surname: this.user.surname,
                            email: this.user.email,
                            phone: this.user.phone,
                            order: this.user.order,
                        })
                        .then(response => {
                            this.user.name = ''
                            this.user.surname = ''
                            this.user.email = ''
                            this.user.phone = ''
                            this.user.order = ''
                            this.getUsers()
                            this.getOrders()
                        })
                        .catch(error => {
                            if (error.response.status === 422)
                            {
                                this.validationErrors = error.response.data.errors
                            }
                            console.log(error)
                        })
                } //Редактирование
                else {
                    axios
                        .put(`api/users/${this.user.id}`, {
                            name: this.user.name,
                            surname: this.user.surname,
                            email: this.user.email,
                            phone: this.user.phone,
                            order: this.user.order,
                        })
                        .then(response => {
                            this.user.name = ''
                            this.user.surname = ''
                            this.user.email = ''
                            this.user.phone = ''
                            this.user.order = ''
                            this.getUsers()
                            this.getOrders()
                        })
                        .catch(error => {
                            if (error.response.status === 422)
                            {
                                this.validationErrors = error.response.data.errors
                            }
                            console.log(error)
                        })
                        .finally(() => this.edit = false);
                }
            },
            editUser(user) {
                this.edit = true
                this.user.id = user.id
                this.user.name = user.name
                this.user.surname = user.surname
                this.user.email = user.email
                this.user.phone = user.phone
                this.user.orders = user.orders
            },
            addOrder() {
                axios
                    .post('api/orders', {
                        addOrder: this.user.order,
                        id: this.user.id
                    })
                    .then(response => {
                        this.user.name = ''
                        this.user.surname = ''
                        this.user.email = ''
                        this.user.phone = ''
                        this.user.order = ''
                        this.getUsers()
                        this.getOrders()
                    })
                    .catch(error => {
                        if (error.response.status === 422)
                        {
                            this.validationErrors = error.response.data.errors
                        }
                        console.log(error)
                    })
                    .finally(() => this.edit = false);
            },
            editOrder(order) {
                axios
                    .put(`api/orders/${order.id}`, {
                        edit: order.order
                    })
                    .then(response => {
                        this.user.name = ''
                        this.user.surname = ''
                        this.user.email = ''
                        this.user.phone = ''
                        this.user.order = ''
                        this.getUsers()
                        this.getOrders()
                    })
                    .catch(error => {
                        if (error.response.status === 422)
                        {
                            this.validationErrors = error.response.data.errors
                        }
                        console.log(error)
                    })
                    .finally(() => this.edit = false);
            },
            deleteUser(id){
                axios
                    .delete(`/api/users/${id}`)
                    .then(response => {
                        this.getUsers()
                        this.getOrders()
                    })
                    .catch(error => console.log(error))
            },
            deleteOrder(id) {
                axios
                    .delete(`/api/orders/${id}`)
                    .then(response => {
                        this.user.name = ''
                        this.user.surname = ''
                        this.user.email = ''
                        this.user.phone = ''
                        this.user.order = ''
                        this.getUsers()
                        this.getOrders()
                    })
                    .catch(error => console.log(error))
            },
            getSearch(search) {
                    if (search === '')
                    {
                        this.errored = true
                        return;
                    }
                axios
                    .get(`/api/users/${search}`)
                    .then(response => {
                        this.user = response.data
                        this.search = ''
                        console.log( this.user)
                    })
                    .catch(error => {
                        if (error.response.status === 422)
                        {
                            this.validationErrors = error.response.data.errors
                        }
                        console.log(error)
                        this.errored = true
                        this.search = ''
                        this.getUsers()
                        this.getOrders()
                    })
                    .finally(() => this.edit = true);
            }

        }
    }
</script>

<style scoped>

</style>
