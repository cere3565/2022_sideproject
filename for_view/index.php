<!DOCTYPE html>
<html lang="zh">
	<head>
		<meta charset="UTF-8">
		<title>alarm clock 介面測試</title>
		<link rel="stylesheet" type="text/css" href="../for_css/normalize.css">
    <link rel="stylesheet" type="text/css" href="../for_css/font.css">
		<link rel="stylesheet" type="text/css" href="../for_css/alarm_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css">  
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vuetify@2.1.6/dist/vuetify.min.css">
    
    <script src="../for_js/src/polyfill.min.js"></script>
		<script src="../for_js/src/vue.min.js"></script>
		<script src="../for_js/src/axios.min.js"></script>
    <script src="../for_js/src/vuetify.min.js"></script>
		<script src="../for_js/src/FileSaver.js"></script>
	</head>

	<body>
		<div class="Header"><h1>鬧鐘設置</h1><hr/></div>
		<div id="app">
			<v-app id="inspire" class="body">
				<v-data-table :headers="headers" :items="alarmclock" sort-by="Model" class="my-table">
					<template v-slot:top>
						<v-toolbar flat color="white">
							<v-spacer></v-spacer>
							<v-dialog v-model="dialog" max-width="1200px">
								<template v-slot:activator="{ on }">
									<v-btn color="primary" dark class="mb-2" v-on="on">新增鬧鐘</v-btn>
									<v-spacer></v-spacer>
									<v-file-input placeholder="上傳鬧鐘資料" prepend-icon="mdi-import" dense
																v-model="upLoadJSON"  @change="getJsonData" accept=".json">
									</v-file-input>
								</template>

								<v-card>
									<v-card-title>
										<span class="headline">{{ formTitle }}</span>
										<v-spacer></v-spacer>
										<v-icon v-if="editedIndex !== -1" large color="orange darken-2" class="mr-2" @click="download"> download </v-icon>
									</v-card-title>
									<v-card-text>
										<v-container>
											<v-flex xs12>
												<v-text-field v-bind:disabled="editedIndex !== -1" v-model="editedItem.ClockName" label="鬧鐘名稱"/>
											</v-flex>

											<v-flex xs12>
												<v-layout row align-center>
													<v-subheader>模式：</v-subheader>
													<v-radio-group v-bind:disabled="editedIndex !== -1" v-model="editedItem.Model" row>
														<v-radio label="自動" value="自動"></v-radio>
														<v-radio label="手動" value="手動"></v-radio>
													</v-radio-group>
												</v-layout>
											</v-flex>
											
											<v-flex xs12>
												<v-subheader><v-text-field @keyup.enter="addMonitor" v-model="newMonitor" label="+ 監控欄位" /></v-subheader>
												<v-radio-group v-bind:disabled="editedIndex !== -1" v-model="editedItem.MonitorList" row>
													<v-radio cols="4" md="3" v-for="(item,index) in settinglist" :key="index" 
																	:label="item['MonitorName']" :value="item['MonitorName']"></v-radio>
												</v-radio-group>
											</v-flex>

											<v-flex xs25>
												<v-list-item>
													<v-list-item-content>
														<v-list-item-title>提醒設置</v-list-item-title>
														<v-list-item-subtitle v-for="(item, index) in clocklist" :key="index">
															<v-row>															
																<v-col cols="5" md="2">
																	<v-text-field v-model="item.AlarmNum" label="到期前" type="number" step="any" min="0"/>
																</v-col>
																<v-col cols="1"><v-subheader>天</v-subheader></v-col>
																<v-col cols="5" md="2">
																	<v-text-field v-model="item.IntervalTime" label="間隔" type="number" step="any" min="0"/>
																</v-col>
																<v-col cols="1"><v-subheader>天</v-subheader></v-col>
																<v-col cols="3" md="2">
																	<v-btn color="second" dark class="mb-2" v-on:click="addItem" v-if="clocklist.length - 1 <= index">+</v-btn>
																	<v-btn color="second" red class="mb-6" v-on:click="removeItem(index);" 
																				 v-if="(clocklist.length - 1 >= index) && (clocklist.length -1 != index)">-</v-btn>
																</v-col>																												
															</v-row>
															<v-divider></v-divider>
														</v-list-item-subtitle>
													</v-list-item-content>
												</v-list-item>
											</v-flex>

											<v-flex xs12>												
												<v-list-item three-line>
													<v-list-item-content>
														<v-list-item-title>適用專案：</v-list-item-title>
														<v-list-item-subtitle>
															<v-row>
																<v-col cols="5" md="2"><v-select v-model="newMatchPJ" @change="MatchData" :items="PjKind" label="案件種類"></v-select></v-col>											
																<v-col cols="5" md="2"><v-autocomplete v-model="AttrLV1" :items="LV1" label="LV1"></v-autocomplete></v-col>																	
																<v-col cols="5" md="2"><v-autocomplete v-model="AttrLV2" :items="LV2" label="LV2"></v-autocomplete></v-col>
																<v-col cols="5" md="2"><v-autocomplete v-model="AttrLV3" :items="LV3" label="LV3"></v-autocomplete></v-col>
																<v-col cols="3" md="2"><v-btn @click="addAttr" color="second" dark class="mb-2">+</v-btn></v-col>																									
															</v-row>
														</v-list-item-subtitle>
														<v-list-item-subtitle>
															<v-chip class="tag_span" v-for="(item,index) in editedItem.MatchPJ" :key="index">
															<v-btn v-show="editedIndex === -1" text fab small @click="deleteMatch(item)">x</v-btn>{{item}}</v-chip>
														</v-list-item-subtitle>
													</v-list-item-content>
												</v-list-item>
											</v-flex>

											<v-flex xs12>
												<v-list-item three-line>
													<v-list-item-content>
														<v-list-item-title><v-list-item-title>排除條件：</v-list-item-title></v-list-item-title>
														<v-list-item-subtitle>
															<v-row>
																<v-col cols="5" md="2"><v-select v-model="newNoMPJ" @change="LvData" :items="PjKind" label="案件種類" ></v-select></v-col>
																<v-col cols="5" md="2"><v-autocomplete v-model="NoMLV1" :items="LV1" label="LV1"></v-autocomplete></v-col>																	
																<v-col cols="5" md="2"><v-autocomplete v-model="NoMLV2" :items="LV2" label="LV2"></v-autocomplete></v-col>
																<v-col cols="5" md="2"><v-autocomplete v-model="NoMLV3" :items="LV3" label="LV3"></v-autocomplete></v-col>
																<v-col cols="3" md="2"><v-btn @click="addNoM" color="second" dark class="mb-2">+</v-btn></v-col>																									
															</v-row>
														</v-list-item-subtitle>
														<v-list-item-subtitle>
															<v-chip class="tag_span" v-for="(item,index) in editedItem.NoMatch" :key="index">
															<v-btn v-show="editedIndex === -1" text fab small @click="deleteNoM(item)">x</v-btn>{{item}}</v-chip>
														</v-list-item-subtitle>
													</v-list-item-content>
												</v-list-item>
											</v-flex>

											<v-flex xs12>
												<v-list-item two-line>
													<v-list-item-content>
														<v-list-item-title>通知部門(內部)：</v-list-item-title>
														<v-list-item-subtitle>
															<v-row>
																<v-col cols="3" md="2" v-for="(item,index) in inNotifier" :key="index">
																	<v-checkbox :label="item['option']" :value="item['option']" v-model="editedItem.InNotifierList"></v-checkbox>
																</v-col>
															<v-row>	
														</v-list-item-subtitle>
													</v-list-item-content>
												</v-list-item>
											</v-flex>
											
											<v-flex xs12>
												<v-list-item two-line>
													<v-list-item-content>
														<v-list-item-title>通知部門(外部)：</v-list-item-title>
														<v-list-item-subtitle>
															<v-row>																
																<v-col cols="3" md="2" v-for="(item,index) in outNotifier" :key="index">
																	<v-checkbox :label="item['option']" :value="item['option']" v-model="editedItem.OutNotifierList"></v-checkbox>
																</v-col>
															</v-row>
														<v-list-item-subtitle>
													<v-list-item-content>
												</v-list-item>
											</v-flex>

										</v-container>
									</v-card-text>
			
									<v-card-actions>
										<v-spacer></v-spacer>
										<v-btn color="blue darken-1" text @click="close">取消</v-btn>
										<v-btn color="blue darken-1" text @click="save">儲存</v-btn>
									</v-card-actions>
								</v-card>
							</v-dialog>
						</v-toolbar>
					</template>
					<template v-slot:item.action="{ item }">
						<v-icon small @click="editItem(item)"> edit </v-icon>
						<v-icon small @click="deleteItem(item)"> delete </v-icon>
					</template>
					<template v-slot:no-data>
						<v-subheader>請新增資料</v-subheader>
					</template>
				</v-data-table>
			</v-app>
		</div>
	</body>		
	<script src="../for_js/index.js"></script>        
</html>