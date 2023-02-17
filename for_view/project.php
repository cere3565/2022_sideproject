<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>專案管理</title>
  
  <link rel="stylesheet" type="text/css" href="../for_css/normalize.css">
  <link rel="stylesheet" type="text/css" href="../for_css/font.css">
  <link rel="stylesheet" type="text/css" href="../for_css/project_style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css">  
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vuetify@2.1.6/dist/vuetify.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cxlt-vue2-toastr@1.1.1/dist/css/cxlt-vue2-toastr.min.css">
  
  <script src="../for_js/src/polyfill.min.js"></script>
  <script src="../for_js/src/vue.min.js"></script>
  <script src="../for_js/src/vuex.global.js"></script>
  <script src="../for_js/src/axios.min.js"></script>
  <script src="../for_js/src/vuetify.min.js"></script>
  <script src="../for_js/src/moment.js"></script>
  <script src="../for_js/src/jquery-3.4.1.min.js"></script>
  <script src="../for_js/src/cxlt-vue2-toastr.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/1.0.8/push.min.js"></script>
</head>

<body>
  <div class="Header"><h1>專案管理</h1><hr/>
  </div>
  <div id="app"> 
    <v-app id="inspire" class="body">
      <v-data-table :headers="headers" :items="project" sort-by="Deadline" class="my-table">
        <template v-slot:top>
          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on }">
              <v-toolbar flat>
                <v-btn color="primary" dark class="mb-2" v-on="on">登入</v-btn>
                <v-spacer></v-spacer>            
                <v-toolbar-title>{{ loginTitle }}</v-toolbar-title>
              </v-toolbar>
              <v-divider></v-divider>            
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">使用者登入</span>
                <v-spacer></v-spacer>
              </v-card-title>
                
              <v-card-text>
                <v-container>
                  <v-flex xs12>
                    <v-text-field label="使用者帳戶" v-model="userAccount"/>
                  </v-flex>
                  <v-flex xs12>
                    <v-text-field label="使用者密碼" v-model="userpws"/>
                  </v-flex>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close">quit</v-btn>
                    <v-btn color="blue darken-1" @click="checklogin" text >login</v-btn>
                  </v-card-actions>
                </v-container>
              </v-card-text>
            </v-card>
          </v-dialog>
        </template>
        <template v-slot:item.action="{ item }">
          <v-row>
            <v-col cols="4"><v-icon color="blue darken-2" @click = "getPjInfo(item)">alarmplus</v-icon></v-col>
            <v-col cols="4"><v-icon color="blue darken-2" @click="finishlist(item)">check</v-icon></v-col>	
          </v-row>
        </template>
        <template v-slot:no-data>
          <v-subheader>請新增資料</v-subheader>
        </template>
      </v-data-table>
    </v-app> 
  </div>
    
</body>
<script src="../for_js/project.js"></script>
</html>