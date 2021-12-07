<template>
    <div>
        <card-component title="Marcas">
            <form>
                <div class="row">
                    <div class="col-sm-6 form-group">
                            <label>ID</label>
                            <input class="form-control" type="text" name="id" v-model="search.id">
                    </div><!-- form-group -->
                    <div class="col-sm-6 form-group">
                        <label>Nome</label>
                        <input class="form-control" type="text" name="name" v-model="search.name">
                    </div><!-- form-group -->
                </div><!-- row -->
            </form>
            <button class="btn btn-primary" @click="research()">Buscar</button>
        </card-component>

        <card-component title="Relação de Marcas">
            <table-component>
                <template v-slot:thead>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Imagem</th>
                    <th scope="col"></th>
                </template>
                <template v-slot:tbody>
                    <tr v-for="brand, key in brands.data" :key="key">
                        <th scope="row">{{ brand.id }}</th>
                        <td>{{ brand.name }}</td>
                        <td><img :src="'storage/' + brand.image" height="50px"></td>
                        <td class="text-right">
                            <button class="btn btn-outline-primary btn-sm m-1" data-toggle="modal" data-target="#view" @click="setStore(brand)">Visializar</button>  
                            <button class="btn btn-primary btn-sm m-1" data-toggle="modal" data-target="#update" @click="setStore(brand)">Atualizar</button>  
                            <button class="btn btn-danger btn-sm m-1" data-toggle="modal" data-target="#delete" @click="setStore(brand)">Remover</button>  
                        </td>
                    </tr>
                </template>
            </table-component>

            <nav aria-label="Page navigation example" style="cursor: pointer;">
                <ul class="pagination">
                    <li :class="link.active ? 'page-item active' : 'page-item'" v-for="link, key in brands.links" :key="key">
                        <a class="page-link" @click="paginate(link)" v-html="link.label"></a>
                    </li>
                </ul>
            </nav>
            
            <!-- Modal Create -->
            <modal-component id="createBrand" title="Nova Marca">
                <template v-slot:body>

                    <alert-component v-if="transferStatus == 'error'" type="danger" title="Erro ao cadastrar marca!">
                        {{ alertMessage.message }}
                        <ul>
                            <li v-for="error, key in alertMessage.errors" :key="key">{{ error[0] }}</li>
                        </ul>
                    </alert-component>

                    <alert-component v-if="transferStatus == 'success'" type="success" title="Marca cadastada com sucesso">
                        {{ alertMessage }}
                    </alert-component>

                    <form>
                        <div class="form-group">
                            <label>Nome</label>
                            <input class="form-control" type="text" name="name" v-model="name">
                        </div><!-- form-group -->
                        <div class="form-group">
                            <label>Logo da Marca</label>
                            <input class="form-control-file" type="file" name="image" @change="loadFile($event)">
                        </div><!-- form-group -->
                    </form>
                </template>
                <template v-slot:footer>
                    <button @click="save()" class="btn btn-primary">Cadastrar</button>
                </template>
            </modal-component>

            <!-- Modal update -->
            <modal-component id="update" title="Atualizar Marca">
                <template v-slot:body>

                    <alert-component v-if="transferStatus == 'error'" type="danger" title="Erro ao cadastrar marca!">
                        {{ alertMessage.message }}
                        <ul>
                            <li v-for="error, key in alertMessage.errors" :key="key">{{ error[0] }}</li>
                        </ul>
                    </alert-component>


                    <alert-component v-if="transferStatus == 'success'" type="success" title="Marca atualizada com sucesso">
                        {{ alertMessage }}
                    </alert-component>

                    <form>
                        <div class="form-group">
                            <label>Nome</label>
                            <input class="form-control" type="text" name="name" v-model="$store.state.model.name">
                        </div><!-- form-group -->
                        <div class="form-group">
                            <label>Logo da Marca</label>
                            <input class="form-control-file" type="file" name="image" @change="loadFile($event)">
                        </div><!-- form-group -->
                    </form>
                </template>
                <template v-slot:footer>
                    <button @click="update()" class="btn btn-primary">Atualizar</button>
                </template>
            </modal-component>

            <!-- Modal View -->
            <modal-component id="view" title="Marca">
                <template v-slot:body>
                    <div class="form-group">
                        <label>ID</label>
                        <input class="form-control" type="text" name="name" v-model="$store.state.model.id" readonly>
                    </div><!-- form-group -->

                    <div class="form-group">
                        <label>Nome</label>
                        <input class="form-control" type="text" name="name" v-model="$store.state.model.name" readonly>
                    </div><!-- form-group -->

                    <div class="form-group">
                        <label>Data de Cadastro</label>
                        <input class="form-control" type="text" name="name" :value="$store.state.model.created_at | formatDate" readonly>
                    </div><!-- form-group -->
                    <div class="form-group">
                        <label>Imagem</label> <br>
                        <img :src="'storage/' + $store.state.model.image" height="50px">
                    </div><!-- form-group -->
                </template>
            </modal-component>

            <!-- Modal Delete -->
            <modal-component id="delete" title="Remover Marca">
                <template v-slot:body>
                    <alert-component v-if="transferStatus == 'error'" type="danger" title="Erro!">
                        {{ alertMessage }}
                    </alert-component>
                    <alert-component v-if="transferStatus == 'success'" type="success" title="Sucesso!">
                        {{ alertMessage }}
                    </alert-component>
                    <div v-if="transferStatus == ''">
                        <div class="form-group">
                            <label>ID</label>
                            <input class="form-control" type="text" name="name" v-model="$store.state.model.id" readonly>
                        </div><!-- form-group -->

                        <div class="form-group">
                            <label>Nome</label>
                            <input class="form-control" type="text" name="name" v-model="$store.state.model.name" readonly>
                        </div><!-- form-group -->

                        <div class="form-group">
                            <label>Imagem</label> <br>
                            <img :src="'storage/' + $store.state.model.image" height="50px">
                        </div><!-- form-group -->
                    </div>
                </template>
                <template v-slot:footer v-if="transferStatus == ''">
                    <button @click="remove($store.state.model.id)" class="btn btn-danger">Remover</button>
                </template>
            </modal-component>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createBrand">
                Nova Marca
            </button>
            
        </card-component>
    </div>
</template>

<script>
export default {
    data() {
        return {
            baseUrl: 'http://127.0.0.1:8000/api/brand',
            paginateUrl: '',
            searchUrl: '',
            name: '',
            logo: [],
            transferStatus: '',
            alertMessage: '',
            brands: [],
            search: {
                id: '',
                name: '',
            }
        }
    },
    methods: {
        setStore(obj) {
            this.$store.state.model = obj
            this.alertMessage = ''
            this.transferStatus = ''
        },
        loadBrands(){
            let url = this.baseUrl + '?' + this.paginateUrl + this.searchUrl

            axios.get(url)
            .then(response => {
                this.brands = response.data
            })
        },
        paginate(link) {
            if (link.url) {
                this.paginateUrl = link.url.split('?')[1]
                this.loadBrands()
            }
        },
        research() {
            this.paginateUrl = 'page=1'
            let filter = ''
            for (let key in this.search) {
                if (this.search[key]) {
                    filter+= key + ',like,%' + this.search[key] + '%;'
                }
            }
            this.searchUrl = '&filters=' + filter
            this.loadBrands()
        },
        update() {
            let url = this.baseUrl + '/' + this.$store.state.model.id
            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            }
            let formData = new FormData()
            formData.append('_method', 'patch')
            formData.append('name', this.$store.state.model.name)
            if (this.logo[0]) {
                formData.append('image', this.logo[0])
            }
            axios.post(url, formData, config)
                .then(response => {
                    this.transferStatus = 'success'
                    this.alertMessage = "ID do registro: " + response.data.id
                    this.loadBrands()
                })
                .catch(errors => {
                    this.transferStatus = 'error'
                    this.alertMessage = errors.response.data
                })
        },
        remove(id) {
            let url = this.baseUrl + '/' + id
            
            let formData = new FormData();
            formData.append('_method', 'delete')
            
            axios.post(url, formData)
                .then(resource => {
                    this.transferStatus = 'success'
                    this.alertMessage = resource.data.message
                    this.loadBrands()
                })
        },
        loadFile(e) {
            this.logo = e.target.files 
        },
        save() {
            let formData = new FormData();
            formData.append('name', this.name)
            formData.append('image', this.logo[0])

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            }

            axios.post(this.baseUrl, formData, config)
                .then(response => {
                    this.transferStatus = 'success'
                    this.alertMessage = "ID do registro: " + response.data.id
                    this.loadBrands()
                })
                .catch(errors => {
                    this.transferStatus = 'error'
                    console.log(errors.response)
                    this.alertMessage = errors.response.data
                })
        }
    },
    mounted() {
        this.loadBrands()
    },
}
</script>