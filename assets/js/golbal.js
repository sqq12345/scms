/*! author：陈成 */
(window.webpackJsonp=window.webpackJsonp||[]).push([[3],{0:function(e,t,a){"use strict";(function(e,i){Object.defineProperty(t,"__esModule",{value:!0}),t.openTable=t.backgroundImg=t.openRefund=t.openModifyAdd=t.openYunDan=t.imgADD=t.uuid=t.putExtra=t.uploadConfig=t.config=t.act=t.requestUrl=void 0;var n=a(2),l={mainUrl:"https://api.shichamaishou.com/",codeurl:"https://api.shichamaishou.com/application/libraries/verification.php",token:"",type:""},r={login_validate:"purchase_login",token_validate:"token_validate",bcProduct_addProduct:"bcProduct_addProduct",bcCategory_getDropList:"bcCategory_getDropList",bcBrand_bcGetList:"bcBrand_bcGetList",bcCategory_getList:"bcCategory_getList",bcCategory_updateStatus:"bcCategory_updateStatus",bcCategory_getParentList:"bcCategory_getParentList",bcCategory_add:"bcCategory_add",bcCategory_getInfo:"bcCategory_getInfo",bcCategory_update:"bcCategory_update",bcTemplate_getList:"bcTemplate_getList",bcTemplate_add:"bcTemplate_add",bcTemplate_updateStatus:"bcTemplate_updateStatus",bcTemplate_update:"bcTemplate_update",bcTemplate_getAllList:"bcTemplate_getAllList",bcProduct_addSku:"bcProduct_addSku",bcProduct_getList:"bcProduct_getList",bcProduct_updateStatus:"bcProduct_updateStatus",bcBespeakCategory_getList:"bcBespeakCategory_getList",bcBespeakCategory_updateStatus:"bcBespeakCategory_updateStatus",bcBespeakCategory_getParentList:"bcBespeakCategory_getParentList",bcBespeakCategory_getInfo:"bcBespeakCategory_getInfo",bcBespeakCategory_update:"bcBespeakCategory_update",bcBespeakCategory_add:"bcBespeakCategory_add",bcProduct_getInfo:"bcProduct_getInfo",bcBrand_add:"bcBrand_add",bcBrand_getList:"bcBrand_getList",bcBrand_updateStatus:"bcBrand_updateStatus",bcBrand_update:"bcBrand_update",bcBrand_getInfo:"bcBrand_getInfo",bcBanner_getList:"bcBanner_getList",bcBanner_updateStatus:"bcBanner_updateStatus",bcBanner_add:"bcBanner_add",bcBanner_update:"bcBanner_update",bcPurchase_getList:"bcPurchase_getList",bcPurchase_add:"bcPurchase_add",bcPurchase_update:"bcPurchase_update",bcCustomer_getList:"bcCustomer_getList",bcPurchase_updateStatus:"bcPurchase_updateStatus",bcCustomer_getInfo:"bcCustomer_getInfo",bcCustomer_updateStatus:"bcCustomer_updateStatus",address_getAreasByCity:"address_getAreasByCity",address_getCitiesByProvince:"address_getCitiesByProvince",address_getProvinces:"address_getProvinces",bcCustomer_update:"bcCustomer_update",bcAddress_update:"bcAddress_update",reBcProduct_addProduct:"reBcProduct_addProduct",bcCategory_getAllList:"bcCategory_getAllList",reBcProduct_getList:"reBcProduct_getList",reBcProduct_updateStatus:"reBcProduct_updateStatus",reBcProduct_addSku:"reBcProduct_addSku",reBcProduct_getInfo:"reBcProduct_getInfo",reBcProduct_updateProduct:"reBcProduct_updateProduct",reBcProduct_updateSku:"reBcProduct_updateSku",reBcProductSku_updateStatus:"reBcProductSku_updateStatus",reBcBespeakProduct_addProduct:"reBcBespeakProduct_addProduct",bcBespeakCategory_getAllList:"bcBespeakCategory_getAllList",reBcBespeakProduct_getList:"reBcBespeakProduct_getList",reBcBespeakProduct_updateStatus:"reBcBespeakProduct_updateStatus",reBcBespeakProductSku_updateStatus:"reBcBespeakProductSku_updateStatus",reBcBespeakProduct_addSku:"reBcBespeakProduct_addSku",reBcBespeakProduct_getInfo:"reBcBespeakProduct_getInfo",reBcBespeakProduct_updateProduct:"reBcBespeakProduct_updateProduct",reBcBespeakProduct_updateSku:"reBcBespeakProduct_updateSku",reBcSpecialProduct_getList:"reBcSpecialProduct_getList",reBcSpecialProduct_getProductList:"reBcSpecialProduct_getProductList",reBcSpecialProduct_getSkuList:"reBcSpecialProduct_getSkuList",reBcSpecialProduct_add:"reBcSpecialProduct_add",reBcSpecialProduct_getInfo:"reBcSpecialProduct_getInfo",reBcSpecialProduct_update:"reBcSpecialProduct_update",reConfig_getInfo:"reConfig_getInfo",reConfig_doSubmit:"reConfig_doSubmit",upload_upload:"upload_upload",bcOrder_getList:"bcOrder_getList",bcOrder_cancel:"bcOrder_cancel",bcOrder_getInfo:"bcOrder_getInfo",company_getList:"company_getList",bcOrder_batchDelivery:"bcOrder_batchDelivery",bcOrder_updateAddress:"bcOrder_updateAddress",bcBespeakOrder_getList:"bcBespeakOrder_getList",bcBespeakOrder_cancel:"bcBespeakOrder_cancel",bcBespeakOrder_getInfo:"bcBespeakOrder_getInfo",bcBespeakOrder_batchDelivery:"bcBespeakOrder_batchDelivery",bcBespeakOrder_updateAddress:"bcBespeakOrder_updateAddress",bcBespeakOrder_replaceOrder:"bcBespeakOrder_replaceOrder",bcBespeakOrder_takingOrder:"bcBespeakOrder_takingOrder",bcBespeakOrder_confirm:"bcBespeakOrder_confirm",bcBespeakOrder_confirmNoGoods:"bcBespeakOrder_confirmNoGoods",bcRefundOrder_getList:"bcRefundOrder_getList",bcRefundOrder_getInfo:"bcRefundOrder_getInfo",bcRefundOrder_reject:"bcRefundOrder_reject",bcRefundOrder_validate:"bcRefundOrder_validate",bcBespeakOrder_getAssignOrderInfo:"bcBespeakOrder_getAssignOrderInfo",bcBespeakOrder_assign:"bcBespeakOrder_assign",bcOrder_refund:"bcOrder_refund",bcBespeakOrder_refund:"bcBespeakOrder_refund",uploadExcel_process:"uploadExcel_process",reBcProduct_validateSkuRef:"reBcProduct_validateSkuRef",bcBespeakOrder_getDelivery:"bcBespeakOrder_getDelivery",bcOrder_getDelivery:"bcOrder_getDelivery",uploadExcel_updateStock:"uploadExcel_updateStock",bcSplitOrder_getList:"bcSplitOrder_getList",bcSplitOrder_delivery:"bcSplitOrder_delivery",bcSplitOrder_getDelivery:"bcSplitOrder_getDelivery",reConfig_getRegionList:"reConfig_getRegionList"};r.reConfig_getRegionList="reConfig_getRegionList",r.bcSplitOrder_getInfo="bcSplitOrder_getInfo";var c=void 0,s={postage:"postage"},u={useCdnDomain:!1,region:i.region.z0};t.requestUrl=l,t.act=r,t.config=s,t.uploadConfig=u,t.putExtra={params:{},mimeType:["image/gif","image/jpeg","image/jpg","image/png"]},t.uuid=function(){for(var e=[],t=0;t<36;t++)e[t]="0123456789abcdef".substr(Math.floor(16*Math.random()),1);e[14]="4",e[19]="0123456789abcdef".substr(3&e[19]|8,1),e[8]=e[13]=e[18]=e[23]="-";var a=e.join("");return a},t.imgADD={add:"http://upload.hljr.com.cn/",productImg:"",bannerImg:"",brandImg:"",idcard:""},t.openYunDan=function(t){c||(0,n.echo)().data({act:r.company_getList}).loading().succ(function(e){e&&(c=e)}).async(),layer.open({type:1,area:["600px","600px"],content:'<div class="layui-form" style="margin-top: 20px"  lay-filter="yundan-form" id="yundan-form">\n               <div class="layui-form-item">\n              <label  class="layui-form-label label-w120">运输公司</label>\n               <div class="layui-input-block" >\n                  '+function(e){var t="";for(var a in e)t+='<input type="radio" name="wuliu" value='+e[a].code+' title="'+e[a].name+'" class="yundan-com">';return t}(c)+'\n    \n\n      \n        </div>\n        </div>\n        <div class="layui-form-item" >\n              <label  class="layui-form-label label-w120">运单号</label>\n               <div class="layui-input-inline" style="width: 250px">\n                <input  type="text" id="yundan-no" placeholder="请输入运单号"  class="layui-input" >\n        </div>\n    </div>\n    <div class="padding15">\n    <ul class="layui-timeline yundan-info">\n    \n   '+(t.splitOrderSn||t.orderSn?' <li class="layui-timeline-item">\n    <i class="layui-icon layui-timeline-axis">&#xe63f;</i>\n    <div class="layui-timeline-content layui-text">\n      <p>\n      获取物流信息中\n      </p>\n    </div>\n  </li>':"")+"\n \n\n</ul>\n</div>\n</div>",btn:["确定","取消"],closeBtn:0,title:"运单信息",yes:function(a,i){var n=e("#yundan-form .layui-form-radioed").prev().val();if(!n)return e.tips("请选择物流公司",4),!1;var l=e("#yundan-no").val();if(!l)return e.tips("请输入运单号",4),!1;t.succ(n,l),layer.close(a)},btn2:function(e,t){layer.close(e)}}),(t.splitOrderSn||t.orderSn)&&(0,n.echo)().data({act:t.yundanInfoAct,splitOrderSn:t.splitOrderSn,orderSn:t.orderSn}).succ(function(t){var a="";for(var i in t.list)a+=' <li class="layui-timeline-item">\n    <i class="layui-icon layui-timeline-axis">&#xe63f;</i>\n    <div class="layui-timeline-content layui-text">\n      <p>\n      '+t.list[i].time+":"+t.list[i].context+" \n      </p>\n    </div>\n  </li>";e(".yundan-info").html(a)}).post(),t.form&&t.form.render(null,"yundan-form")},t.openModifyAdd=function(t){layer.open({type:1,content:'<div class="layui-form"  id="modify-add"  lay-filter="modify-add-form">\n    <div class="layui-form-item" style="margin-top: 20px">\n              <label  class="layui-form-label label-w120">收件人</label>\n               <div class="layui-input-inline">\n                <input type="text"  placeholder="收件人"  name="receiverName" class="layui-input" lay-verify="must" must="收件人不能为空">\n        </div>\n    </div>\n        <div class="layui-form-item" >\n              <label  class="layui-form-label label-w120">联系电话</label>\n               <div class="layui-input-inline">\n                <input type="text" placeholder="联系电话"  name="receiverPhone" class="layui-input"  lay-verify="telephone" info="手机号错误">\n        </div>\n    </div>\n    <div class="province-city-area">\n               <div class="layui-form-item">\n              <label  class="layui-form-label label-w120">省份</label>\n               <div class="layui-input-inline">\n              <select  name="province" lay-filter="modify-add-province-city-area" lay-verify="must" must="请选择省份">\n              <option value="" code="">请选择</option>\n              \n                '+function(e){var t="";for(var a in e)t+='<option value="'+e[a].name+'" code="'+e[a].code+'">'+e[a].name+"</option>";return t}(t.province)+'\n      </select>\n        </div>\n        </div>\n               <div class="layui-form-item">\n              <label  class="layui-form-label label-w120">城市</label>\n               <div class="layui-input-inline">\n              <select name="city" lay-filter="modify-add-province-city-area" lay-verify="must" must="请选择城市">\n        <option value="" ></option>\n      </select>\n        </div>\n        </div>\n               <div class="layui-form-item">\n              <label  class="layui-form-label label-w120">区</label>\n               <div class="layui-input-inline">\n              <select name="area"  lay-verify="must" must="请选择区域">\n        <option value="" ></option>\n      </select>\n        </div>\n        </div>\n        </div>\n        <div class="layui-form-item" >\n              <label  class="layui-form-label label-w120">详细地址</label>\n               <div class="layui-input-inline">\n                <input type="text"  placeholder="详细地址"  class="layui-input" name="address" lay-verify="must" must="详细地址不能为空">\n        </div>\n    </div>\n        <div class="layui-form-item" >\n              <label  class="layui-form-label label-w120">邮编</label>\n               <div class="layui-input-inline">\n                <input type="text"  placeholder="邮编"  class="layui-input" name="code">\n                <input type="button" lay-submit hidden id="modify-add-btn" lay-filter="'+t.layFilter+'"> \n        </div>\n    </div>\n</div>',btn:["确定","取消"],closeBtn:0,area:["auto","600px"],title:"修改地址",yes:function(t,a){e("#modify-add-btn").click()},btn2:function(e,t){layer.close(e)}}),t.form&&t.form.render(null,"modify-add-form")},t.openRefund=function(t){layer.open({type:1,area:["400px"],content:'<div class="layui-form" style="margin-top: 20px"  lay-filter="refund-form" id="refund-form">\n        <div class="layui-form-item" >\n              <label  class="layui-form-label">退款数量</label>\n               <div class="layui-input-inline" style="width: 250px">\n                <input  type="text" id="refund-num" placeholder="退款数量"  class="layui-input" >\n        </div>\n        </div>\n          <div class="layui-form-item" >\n              <label  class="layui-form-label">退款原因</label>\n               <div class="layui-input-inline" style="width: 250px">\n                <input  type="text" id="refund-result" placeholder="退款原因"  class="layui-input" >\n        </div>\n    </div>\n</div>',btn:["确定","取消"],closeBtn:0,title:"退款",yes:function(a,i){var n=e("#refund-num").val();if(!/^[0-9]+$/.test(n))return e.tips("退款数量必须是整数",4),!1;if(Number.parseInt(n)<1)return e.tips("退款数量必须大于0",4),!1;if(n>t.num)return e.tips("退款数量大于实际数量",4),!1;var l=e("#refund-num").val();if(!l)return e.tips("请输入退款原因",4),!1;t.succ(n,l),layer.close(a)},btn2:function(e,t){layer.close(e)}}),t.form&&t.form.render(null,"yundan-form")},t.backgroundImg=function(e){return"background-image:url("+e+")"},t.openTable=function(e){layer.open({type:1,area:["700px","auto"],content:'\n        <table class="layui-table" style="margin:50px auto;width: 80%">\n        <thead>\n        <tr>\n         <th>品牌</th>\n        <th>名称</th>\n        <th>'+e[0].model+"</th>\n        <th>数量</th>\n</th>\n</thead>\n        "+function(e){var t="";for(var a in e)t+="<tr><td>"+e[a].brandName+"</td><td>"+e[a].productName+"</td><td>"+e[a].attr+"</td><td>"+e[a].num+"</td></tr>";return t}(e)+"\n        </table>\n        "})}}).call(this,a(3),a(5))},2:function(e,t,a){"use strict";(function(e){Object.defineProperty(t,"__esModule",{value:!0}),t.echo=void 0;var i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},n=a(0);t.echo=function(t){var a=new Object;return a.isasync=!1,a.beforeSend=function(){},a.url=t||n.requestUrl.mainUrl,a.post=function(){a.isasync=!0,a.ajax("POST")},a.get=function(){a.isasync=!0,a.ajax("GET")},a.async=function(){a.isasync=!1,a.ajax("POST")},a.data=function(t,i){return e.stringIsBlank(n.requestUrl.token)?(this.data={data:JSON.stringify(t)},this.echa=!0):i?(this.data=t,this.echa=!1):(this.data={data:JSON.stringify(t),token:n.requestUrl.token},this.echa=!0),a},a.loading=function(e,t){return a.beforeSend=function(){switch(t){case 1:this.layerIndex=layer.load(1);break;case 2:this.layerIndex=layer.msg(e,{icon:16,shade:.01,time:0});break;default:this.layerIndex=layer.msg("加载中",{icon:16,shade:.01,time:0})}},a},a.complete=function(){return layer.close(this.layerIndex),a},a.err=function(e){return void 0!=(void 0===e?"undefined":i(e))&&(a.error=e),a},a.succ=function(e){return void 0!=(void 0===e?"undefined":i(e))&&(a.success=e),a},a.dataFilter=function(t){switch(e.stringIsBlank(t)||(t=JSON.parse(t)),t.code){case 200:return t.data||t.datalist||!0;case 600:return layer.msg(""+t.message),n.requestUrl.token="",!1;default:return layer.msg(t.message||"发生未知错误",{icon:5,anim:6}),!1}return!1},a.ajax=function(t){e.ajax(a.url,{type:t,data:this.data,dataType:"text",success:a.success,error:a.error||function(){layer.msg("发生错误，稍后再试")},beforeSend:a.beforeSend,cache:this.echa,processData:this.echa,contentType:!!this.echa&&"application/x-www-form-urlencoded",complete:a.complete,dataFilter:a.dataFilter,async:a.isasync})},a}}).call(this,a(3))},50:function(e,t,a){"use strict";(function(e,t,i){var n=a(2),l=a(0);function r(e,t){s.open({type:1,title:"限购条件",content:"",btn:["每周","每月","每季","每年"],btn4:function(a,i){c({elem:e.target||e,title:this.btn[3],limit1:4,data:t}),s.close(a)},btn3:function(a,i){c({elem:e.target||e,title:this.btn[2],limit1:3,data:t}),s.close(a)},btn2:function(a,i){c({elem:e.target||e,title:this.btn[1],limit1:2,data:t}),s.close(a)},yes:function(a,i){c({elem:e.target||e,title:this.btn[0],limit1:1,data:t}),s.close(a)}})}function c(e){s.prompt({title:"请输入值"},function(a,i,n){t(e.elem).val(e.title+"限购"+a+"件").next().val(e.limit1).next().val(a),e.elem.dispatchEvent(new Event("input")),t(e.elem).next()[0].dispatchEvent(new Event("input")),t(e.elem).next().next()[0].dispatchEvent(new Event("input")),s.close(i)})}e.component("login",{template:"#loginAndeRegister",props:["loginOrRegist","codeurl"]}),e.component("h2-title",{props:["title"],template:'  <fieldset class="layui-elem-field layui-field-title" >\n            <legend v-text="title"></legend>\n        </fieldset>'}),e.component("sku-table",{props:["skuData","modelData","model"],template:'<div>  <div class="layui-form-pane tag-form">\n                                <div class="layui-form-item">\n                                    <label class="layui-form-label">选择模板</label>\n                                    <div class="layui-input-inline">\n                                        <select class="template-select"  lay-search>\n                                            <option v-for="(d,i) in modelData" :value="i" v-text="d.name"  v-if="i==0" selected></option>\n                                            <option  v-text="d.name" :value="i" v-else></option>\n                                        </select>\n                                    </div>\n                                      <div class="layui-inline">\n                                      <button class="layui-btn layui-btn-normal add-template" @click="addModel()">添加</button>\n                                    <button class="layui-btn layui-btn-normal add-template" @click="fullAll()">批量填充数据</button>\n                                    </div>\n                                      <div class="layui-input-inline"> <input  class="layui-input" name="model" :value="model.name" placeholder="sku分类名"></div>\n                                  \n                                </div>\n                                </div>\n                          \n                           \n                            \n <table class="layui-table layui-form sku-table" lay-filter="sku-template-form" > <tr v-for="(data,i) in skuData">\n                                            <td class="sku-td">\n                                            <input name="id" :value="data.id" hidden>\n                                                <div class="layui-form-item layui-inline"><label class="layui-form-label">型号</label><div class="layui-input-inline"><input class="layui-input" v-model="data.attr" name="attr" lay-verify="must"  must="请填写型号名称"></div></div>\n                                                <div class="layui-form-item layui-inline"><label class="layui-form-label">销售价</label><div class="layui-input-inline"><input class="layui-input" v-model="data.price" name="price" lay-verify="price"></div></div>\n                                                <div class="layui-form-item layui-inline"><label class="layui-form-label">成本价</label><div class="layui-input-inline"><input class="layui-input" v-model="data.costPrice" name="costPrice" lay-verify="notmustprice"></div></div>\n                                                <div class="layui-form-item layui-inline"><label class="layui-form-label">免税价</label><div class="layui-input-inline"><input class="layui-input" v-model="data.dutyFreePrice" name="dutyFreePrice" lay-verify="notmustprice"></div></div>\n                                                <div class="layui-form-item layui-inline"><label class="layui-form-label">库存</label><div class="layui-input-inline"><input class="layui-input"  v-model="data.stock" name="stock" lay-verify="notmustint"></div></div>\n                                                <div class="layui-form-item layui-inline"><label class="layui-form-label">ref编码</label><div class="layui-input-inline"><input class="layui-input"  v-model="data.ref" name="ref"></div></div>\n                                                <div class="layui-form-item layui-inline"><label class="layui-form-label">最少购买</label><div class="layui-input-inline"><input class="layui-input"  v-model="data.minPurchaseNum" name="minPurchaseNum"  lay-verify="int"></div></div>\n                                                <div class="layui-form-item layui-inline"><label class="layui-form-label">限制条件</label><div class="layui-input-inline" @click="limit($event)"><input class="layui-input" readonly name="limitTimelimitNum"  v-model="data.limitTimelimitNum"><input hidden   name="limitTime"  v-model="data.limitTime" ><input  name="limitNum"  hidden v-model="data.limitNum"></div></div>\n                                                <div class="layui-form-item layui-inline"><label class="layui-form-label">图片</label><div class="layui-inline"><button class=" layui-btn" @click="addImg($event.target,i)">上传图片</button><input name="sku-img" v-model=\'data.img\' hidden><input type="file" accept="image/gif,image/jpeg,image/jpg,image/png" class="sku-img" @change="change($event.target,i,\'skuImg\')" hidden><div class="backimg-c layui-inline" style="width: 40px;height: 40px" :style=\'"background-image:url("+data.img+")"\'>{{data.percent?data.percent:\'\'}}</div></div></div>\n                                                <div class="layui-form-item layui-inline"><label class="layui-form-label">详情图</label><div class="layui-inline"><button class=" layui-btn" @click="addImg($event.target,i)">上传图片</button><input name="sku-detail-img" v-model=\'data.detailImg\' hidden><input type="file" accept="image/gif,image/jpeg,image/jpg,image/png" class="sku-img" @change="change($event.target,i,\'detailImg\')" hidden><div class="backimg-c layui-inline" style="width: 40px;height: 40px" :style=\'"background-image:url("+data.detailImg+")"\'>{{data.DetialPercent?data.DetialPercent:\'\'}}</div></div></div>\n                                                <div class="layui-form-item layui-inline"><label class="layui-form-label">启用</label>\n                                                <div class="layui-input-inline" v-if="data.status==1">  <input type="checkbox" name="status" lay-skin="switch" lay-text="启用|禁用" checked value="1"></div>\n                                                <div class="layui-input-inline" v-else>  <input type="checkbox" name="status" lay-skin="switch" lay-text="启用|禁用" value="1" ></div>\n                                                </div>\n                                                <div class="layui-form-item layui-inline"><div class="layui-input-inline"><button class="layui-btn layui-btn-danger " @click="del(i)"><i class="layui-icon" >&#xe640;</i>  </button></div></div>\n                                            </td>\n                                        </tr>\n                                           <tr v-if="skuData.length>0">\n                                            <td>\n                                                <button class="layui-btn layui-btn-normal" @click="add()">点击这里增加新的一行</button>\n                                            </td>\n                                        </tr>\n                                           <tr >\n                                            <td  v-if="skuData.length>0">\n                                                <button class="layui-btn" lay-submit lay-filter="sku-template-form-submit" type="reset" style="border: none">提交保存</button>\n                                            </td>\n                                        </tr>\n                                        </table>\n                                        </div> \n',data:function(){return{uploadToken:""}},mounted:function(){},methods:{change:function(e,a,n){this.skuData[a].subscription&&this.skuData[a].subscription.unsubscribe();var r={fname:e.files[0].name,params:{}},c=(0,l.uuid)(),s=this,u={next:function(e){"skuImg"==n?s.skuData[a].percent=Math.round(e.total.percent):s.skuData[a].DetialPercent=Math.round(e.total.percent)},error:function(e){t.tips("上传过程出现错误，稍后再试",4),"skuImg"==n?s.skuData[a].percent=0:s.skuData[a].DetialPercent=0},complete:function(i){"skuImg"==n?s.skuData[a].percent=0:s.skuData[a].DetialPercent=0,s.skuData[a].uploading=!1,"skuImg"==n?(s.skuData[a].img=""+l.imgADD.add+c,t(e).next().css("background-image","url('"+s.skuData[a].img+"')")):(s.skuData[a].detailImg=""+l.imgADD.add+c+l.imgADD.productImg,t(e).next().css("background-image","url('"+s.skuData[a].detailImg+"')"))}},d=i.upload(e.files[0],c,this.uploadToken,r,l.uploadConfig);this.skuData[a].subscription=d.subscribe(u),this.skuData[a].uploading=!0,"skuImg"==n?this.skuData[a].img="true":this.skuData[a].detailImg="true"},addImg:function(e,a){var i=this;return this.uploadToken||(0,n.echo)().data({act:l.act.upload_upload}).succ(function(e){e?i.uploadToken=e.uploadToken:t.tips("上传接口打开失败，请稍后再试",4)}).err(function(){t.tips("上传接口打开失败，请稍后再试",4)}).async(),t(e).next().next().click()},addModel:function(){var e=t(".cloned .template-select").val();for(var a in this.model.name=this.modelData[e].name,this.modelData[e].list)this.add(this.modelData[e].list[a].name)},add:function(e){this.skuData.push({attr:e,status:1,img:""})},del:function(e){var t=this.skuData;s.confirm("删除后，数据不会保留！",{icon:3,title:"提示"},function(a){t[e].id?(0,n.echo)().data({act:l.act.reBcProductSku_updateStatus,id:t[e].id,status:2}).succ(function(i){i&&(t.splice(e,1),s.close(a))}).loading("删除中").post():(t.splice(e,1),s.close(a))})},limit:function(e){r(e,this.skuData)},fullAll:function(){var e=t(".fullAll").clone().children(".layui-form").addClass("cloned").end().html(),a=this.skuData;s.open({title:"批量填充数据",content:e,yes:function(e,i){var n=t(i.selector),l=n.find("input[name=price]").val(),r=n.find("input[name=costPrice]").val(),c=n.find("input[name=dutyFreePrice]").val(),u=n.find("input[name=stock]").val(),d=n.find("input[name=minPurchaseNum]").val(),o=n.find("input[name=limitTimelimitNum]").val(),p=n.find("input[name=limitTime]").val(),m=n.find("input[name=limitNum]").val();for(var y in a)a[y].price=l||a[y].price,a[y].costPrice=r||a[y].costPrice,a[y].dutyFreePrice=c||a[y].dutyFreePrice,a[y].stock=u||a[y].stock,a[y].limitTimelimitNum=o||a[y].limitTimelimitNum,a[y].minPurchaseNum=d||a[y].minPurchaseNum,a[y].limitTime=p||a[y].limitTime,a[y].limitNum=m||a[y].limitNum;s.close(e)}})}},updated:function(){u.render(null,"sku-template-form")}});var s=void 0,u=void 0;t(function(){window.history&&window.history.pushState&&t(window).on("popstate",function(){window.history.pushState("forward",null,"#"),window.history.forward(1),t.tips("本系统不可回退，如需退出关闭页面即可",3)}),window.history.pushState("forward",null,"#"),window.history.forward(1),layui.use(["layer","form"],function(){s=layui.layer,(u=layui.form).on("checkbox(at-least-one)",function(e){if(t(e.elem).parents(".at-least-one").find(".layui-form-checked").length<1)return t(e.othis).click(),void s.msg("至少选择一个",{icon:6})}),u.verify({telephone:function(e,a){if(t.stringIsBlank(e))return t(a).attr("info")},code:function(e,a){if(t.stringIsBlank(e))return t(a).attr("info")},must:function(e,a){if(t.stringIsBlank(e))return t(a).attr("must")},price:function(e,t){if(!/^[0-9]+\.?[0-9]?$/.test(e))return"价格不合法"},notmustprice:function(e,a){if(!t.stringIsBlank(e)){if(!/^[0-9]+\.?[0-9]*$/.test(e))return"价格不合法"}},int:function(e,t){if(!/^[0-9]+$/.test(e))return"必须是非负整数"},notmustint:function(e,a){if(!t.stringIsBlank(e)){if(!/^[0-9]+$/.test(e))return"必须是非负整数"}},greaterthan0:function(e,t){if(Number(e)<=0)return"必须大于0"},selectthan0:function(e,a){if(Number(e)<=0)return"必须选择"+t(a).attr("info")}})}),t("body").on("click",".cloned .limit",function(){r(this)})})}).call(this,a(1),a(3),a(5))}},[[50,0,1]]]);
//# sourceMappingURL=golbal.js.map