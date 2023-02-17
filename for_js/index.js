Vue.use(Vuetify)

var app = new Vue({
	el: "#app",
	vuetify: new Vuetify(),
	data: () => ({		
		intervals: [			
			{ text: '天', value: '天' }
		],
		PjKind:['P','CFP','T','D','D3','EEWE'],
		inNotifier:[{option: '專案業務'},{option: '會計部門'}],
		outNotifier:[{option: '業務'},{option: '會計'},{option: '聯絡人'},{option: '代理人(律師)'},{option: '申請人'},{option: '發明人'}],
		alarmclock:[],
		settinglist:[],
		clockIndex:-1,
		clocklist:[{AlarmNum:'',IntervalTime:''}],
		PJItem:[],
		PJsetting:{},
		LV1: [],LV2: [],LV3: [],
		PJIndex: -1,
		dialog: false,
    headers: [
			{ text: '鬧鐘名稱', value: 'ClockName', sortable: false, align: 'left'},
      { text: '模式', value: 'Model' },
      { text: '監控欄位', value: 'MonitorList' },
      { text: '操作', value: 'action', sortable: false, width :90 },
    ],
    editedIndex: -1,
    editedItem: { ID:'', ClockName: '', Model: '', MonitorList: '',  MatchPJ:[], NoMatch:[],InNotifierList: [],OutNotifierList: []},
    defaultItem: { ClockName: '', Model: '', MonitorList: '', MatchPJ:[], NoMatch:[], InNotifierList: [], OutNotifierList: []},
		
		newMatchPJ:'',
		newNoMPJ:'',		
		AttrLV1: '',AttrLV2: '',AttrLV3: '',
		NoMLV1: '',NoMLV2: '',NoMLV3: '',
		newMonitor:'',

		upLoadJSON: null,
  	upLoadJSONContent: null
	}),
	computed: { 
		formTitle () { return this.editedIndex === -1 ? '增加新鬧鐘' : '編輯鬧鐘' }, 
	},

	watch: {
		dialog (val) { val || this.close() },
	},

	created() { 
		this.getJsonData()					
		this.getPJData()
	 },

	mounted:function(){
		this.getSetting()
		this.getData()
		// setTimeout(() => {this.MatchData(), 1000})
	},
	
	methods: {
		getJsonData(){
			let formData = new FormData();
			if (this.upLoadJSON !== null) {
        //定義reader
        let reader = new FileReader();
        //讀取json
        reader.readAsText(this.upLoadJSON);
        reader.onload = () => {
          //檢測是否存在資料
          if (reader.result !== "") {
            // 將文字轉成json
            this.upLoadJSONContent = JSON.parse(reader.result);
            //儲存至mysql
						formData.append("alarmclock", JSON.stringify(this.upLoadJSONContent.alarmclock))
						formData.append("clocklist", JSON.stringify(this.upLoadJSONContent.clocklist))
						axios.post('../for_system/alarmclock/alarmclock_add.php',formData).then((res)=>{
							console.log(res)
						}).catch(error => {console.log(error)})
					}
        };
				alert("上傳完成！請刷新頁面")
				this.$forceUpdate();
      }				
		},

		//顯示各鬧鐘資訊
		getData(){
			var that = this;
			axios.get("../for_system/alarmclock/clock_get.php").then(function(res){
				that.alarmclock = res.data;
			}).catch(error => {console.log(error)})
		},
		
		//顯示監控欄位設定
		getSetting(){
			var that = this;
			//顯示clock設定選項
			axios.get("../for_system/alarmclock/setting_get.php").then(function(res){
				that.settinglist = res.data;
			}).catch(error => {console.log(error)});
		},

		//實時搜尋監控欄位的鬧鐘清單
		getClockData(){
			var that = this;
			axios.get("../for_system/alarmclock/clocklist_get.php?ID=" + this.editedItem.ID).then(function(res){
				that.clocklist = res.data
			}).catch(error => {console.log(error)})
		}, 

		//顯示專案清單
		getPJData(){
			var that = this;
			axios.get("../for_system/alarmclock/PgSetting_get.php").then(function(res){
				that.PJsetting = res.data;
				console.log('PJsetting=',that.PJsetting)
			}).catch(error => {console.log(error)})
		},	

		MatchData () {			
			this.LvData()
			this.LV1.push('*')
			this.LV2.push('*')
			this.LV3.push('*')
			console.log('LV1=',this.LV1)
    },

		LvData(){
			console.log('PJsetting=',this.PJsetting[0]['[AttrLV1'])
			this.LV1 = this.PJsetting[0]['AttrLV1'].split(",")
			this.LV2 = this.PJsetting[0]['AttrLV2'].split(",")
			this.LV3 = this.PJsetting[0]['AttrLV3'].split(",")
		},

		//儲存鬧鐘設定
		save () {
			let formData = new FormData();
			//更新資料
      if (this.editedIndex > -1) {
        Object.assign(this.alarmclock[this.editedIndex], this.editedItem)
				formData.append("alarmclock", JSON.stringify(this.editedItem))	
				formData.append("clocklist", JSON.stringify(this.clocklist))
				
				axios.post('../for_system/alarmclock/alarmclock_update.php',formData).then((res)=>{
					console.log(res)
				}).catch(error => {console.log(error)})
      }
			//新增資料
			else {				
				formData.append("alarmclock", JSON.stringify(this.editedItem))
				formData.append("clocklist", JSON.stringify(this.clocklist))
        this.alarmclock.push(this.editedItem)
				axios.post('../for_system/alarmclock/alarmclock_add.php',formData).then((res)=>{
					console.log(res)
				}).catch(error => {console.log(error)})
      }
      this.close()
    },
		
		//刪除鬧鐘設定
    deleteItem (item) {
      var that=this;
			this.editedIndex = this.alarmclock.indexOf(item)
      this.editedItem = Object.assign({}, item)
      if(confirm("確定刪除此鬧鐘?刪除後將無法回復")){
				axios.get("../for_system/alarmclock/alarmclock_del.php?ID=" + this.editedItem.ID).then(function(res){
					that.clocklist = res.data
				}).catch(error => {console.log(error)})
				alert("刪除完成！請刷新頁面")
			} 
    },
		
		//刪除監控欄位鬧鐘
		removeItem: function(index) {
			let cno = this.clocklist[index]['CNO']
			if(confirm("確定刪除此監控欄位鬧鐘?")){
				if(cno !== undefined){				
					axios.get("../for_system/alarmclock/del_clocklist.php?id=" + cno).then(function(res){
						that.clocklist = res.data
					}).catch(error => {console.log(error)})
				}
				this.clocklist.splice(index, 1)
			}
    },

		//新增監控欄位
		addMonitor: function() {
			let formData = new FormData();
			if(this.newMonitor!=='' && confirm("確定新增 【" + this.newMonitor +"】 的監控欄位?確定後將無法修改")){				
				formData.append("MonitorName", this.newMonitor);
				axios.post('../for_system/alarmclock/setting_add.php',formData).then((res)=>{
					console.log(res)
				}).catch(error => {console.log(error)})
				this.settinglist.push({MonitorName: this.newMonitor})
			}
			this.newMonitor=''
		},

		//編輯鬧鐘設定
		editItem (item) {
      this.editedIndex = this.alarmclock.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
			this.StrToArr()
			this.getClockData()
			this.getPJData()			
    },

    //關閉編輯視窗
		close () {
      this.dialog = false
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      }, 300)
			//將clocklist初始化
			this.clocklist = this.$options.data().clocklist
    },
	
		//增加監控欄位鬧鐘
		addItem() {			
			this.clocklist.push({
				AlarmNum:this.AlarmNum,
				IntervalTime:this.IntervalTime,
      })					
    },

		//新增符合條件
		addAttr: function() {
			let newAttr=''
			newAttr = this.AttrLV1+ '.' + this.AttrLV2+ '.' + this.AttrLV3
			console.log('MatchPJ=',this.newMatchPJ)
			console.log('attr=',newAttr)
			this.editedItem.MatchPJ.push(this.newMatchPJ + ';'+ newAttr)
			newAttr=''
			this.newMatchPJ=''
			this.AttrLV1=''
			this.AttrLV2=''
			this.AttrLV3=''
		},

		deleteMatch(item) {this.editedItem.MatchPJ.splice(this.editedItem.MatchPJ.indexOf(item), 1)},

		//新增排除條件
		addNoM: function() {
			let newNoM=''
			newNoM = this.NoMLV1+ '.' + this.NoMLV2+ '.' + this.NoMLV3
			this.editedItem.NoMatch.push(this.newNoMPJ + ';'+ newNoM)

			newNoM=''
			this.newNoMPJ=''
			this.NoMLV1=''
			this.NoMLV2=''
			this.NoMLV3=''
		},

		deleteNoM(item) {this.editedItem.NoMatch.splice(this.editedItem.NoMatch.indexOf(item), 1)},

		//將字串根據逗號切割成陣列
		StrToArr: function(){
			this.editedItem.InNotifierList = this.editedItem.InNotifierList.split(",")
			this.editedItem.OutNotifierList = this.editedItem.OutNotifierList.split(",")
			this.editedItem.MatchPJ = JSON.parse(this.editedItem.MatchPJ)
			// this.editedItem.MatchAttr = this.editedItem.MatchAttr.split(",")
			this.editedItem.NoMatch = JSON.parse(this.editedItem.NoMatch)
		},

		download() {
			let file = this.editedItem.ClockName
			let filename = file + ' .json';
			if(confirm("確定下載此鬧鐘")){
				var content = JSON.stringify({
					alarmclock: this.editedItem,
					clocklist: this.clocklist,
				})

				var blob = new Blob([content], {
					type: "text/plain;charset=utf-8"
				});
				console.log(blob)
				saveAs(blob, filename);
			}
		}
	}    
})