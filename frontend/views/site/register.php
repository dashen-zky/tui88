<?php
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

AppAsset::addCss($this,'@web/src/css/login-regist/regist.css?v=' . Yii::$app->params['static_version']);
?>

<!-- 主要内容部分-->
<?php $form = ActiveForm::begin(["id" => 'signup-form',]) ?>
<div class="main-wrap clearfix">
    <div class="main-con">
		<div class="regist-con content">
			<h2>注册</h2>
			<div class="input-group">
				<span class="title">注册手机号 :</span>
				<?= Html::input("text","SignUpForm[phone]",$signup->phone,["class"=>"cell-phone","placeholder"=>"请输入注册手机号"]) ?>
				<div <?= isset($signup->getErrors()['phone']) ? 'class="tips show"':'class="tips"';?> > 
					<?= isset($signup->getErrors()['phone']) ? $signup->getErrors()["phone"][0]:'请输入手机号';?>
				</div>
			</div>
            <div class="input-group code-insure">
				<span class="title">图片验证码 :</span>
                <?= Html::input("text","SignUpForm[verifyCode]",$signup->verifyCode,["class"=>"verifyCode","placeholder"=>"请填写图片验证码"]) ?>
                <?= Captcha::widget([
                    'model' 		=> $signup,
                    'attribute' 	=> 'verifyCode',
                    'captchaAction' => 'site/captcha',
                    'template' 		=> '<span>{image}</span>',
                    'imageOptions' => [
                        'title' 	=> '点击图片刷新',
                        "style" => "cursor:pointer",
                    ]
                ]);?>
				<div <?= isset($signup->getErrors()['verifyCode']) ? 'class="tips show"':'class="tips"';?> >
					<?= isset($signup->getErrors()['verifyCode']) ? $signup->getErrors()["verifyCode"][0]:'请输入图片验证码';?>
				</div>
			</div>

			<div class="input-group code-insure">
				<span class="title">手机验证码 :</span>
				<?= Html::input("text","SignUpForm[verify]",$signup->verify,["class"=>"verify-code","placeholder"=>"请输入手机验证码"]) ?>
				<span class="get-code bg-main color-fff">获取手机验证码</span>
				<span class="unclick color-fff"><i>60</i> s</span>
				<div <?= isset($signup->getErrors()['verify']) ? 'class="tips show"':'class="tips"';?> >
				<?= isset($signup->getErrors()['verify']) ? $signup->getErrors()["verify"][0]:'请输入手机验证码';?>
				</div>
			</div>
			<div class="input-group">
				<span class="title">密码 :</span>
				<?= Html::input("password","SignUpForm[password]",$signup->password,["class"=>"psd","placeholder"=>"请输入6-20位由数字或字母组成的密码"]) ?>
				<div <?= isset($signup->getErrors()['password']) ? 'class="tips show"':'class="tips"';?> >
				<?= isset($signup->getErrors()['password']) ? $signup->getErrors()['password'][0]:'请输入密码';?>
				</div>
			</div>
			<div class="input-group">
				<span class="title">确认密码 :</span>
				<?= Html::input("password","SignUpForm[rePassword]",$signup->rePassword,["class"=>"insure-psd","placeholder"=>"请再输一遍密码"]) ?>
				<div <?= isset($signup->getErrors()['rePassword']) ? 'class="tips show"':'class="tips"';?> >
					<?= isset($signup->getErrors()['rePassword']) ? $signup->getErrors()['rePassword'][0]:'请确保两次密码一致';?>
				</div>
			</div>
			<div class="agree">
                <input type="checkbox" class="tui88-service">
                <span>我同意<a href="javascript:void(0)" data-target="#service-terms" data-toggle="modal">《TUI88服务条款》</a></span>
				<span class="agree_serve">* 请阅读并同意服务条款</span>
			</div>
			<button class="insure-regist btn bg-main color-fff" disabled="disabled">立即注册</button>
		</div>
	</div>
</div>
<!--tui88服务条款 modal层-->
<div id="service-terms" class="service-terms modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="red fl">TUI88服务条款</span><i class="close fr" data-dismiss="modal"></i></div>
            <div class="modal-body">
                &emsp;&emsp;TUI88在此特别提醒您（用户）在注册成为用户之前，请认真阅读本《用户协议》（以下简称“协议”），确保您充分理解本协议中各条款。请您审慎阅读并选择接受或不接受本协议。除非您接受本协议所有条款，否则您无权注册、登录或使用本协议所涉服务。您的注册、登录、使用等行为将视为对本协议的接受，并同意接受本协议各项条款的约束。<br>
                本协议约定TUI88与用户之间关于“TUI88”软件服务（以下简称“服务”）的权利义务。“用户”是指注册、登录、使用本服务的个人。本协议可由TUI88随时更新，更新后的协议条款一旦公布即代替原来的协议条款，恕不再另行通知，用户可在本网站查阅最新版协议条款。在TUI88修改协议条款后，如果用户不接受修改后的条款，请立即停止使用TUI88提供的服务，用户继续使用TUI88提供的服务将被视为接受修改后的协议。<br><br>
                一、账号注册<br>
                &emsp;&emsp;1、用户在使用本服务前需要注册一个“TUI88”账号。“TUI88”账号应当使用电子邮箱或手机号码绑定注册，请用户使用尚未与“TUI88”账号绑定的电子邮箱或手机号码，以及未被TUI88根据本协议封禁的电子邮箱或手机号码注册“TUI88”账号。TUI88可以根据用户需求或产品需要对账号注册和绑定的方式进行变更，而无须事先通知用户。<br>
                &emsp;&emsp;2、鉴于“TUI88”账号的绑定注册方式，您同意TUI88在注册时将自动提取您的手机号码及手机设备识别码等信息用于注册。<br>
                &emsp;&emsp;3、在用户注册及使用本服务时，TUI88需要搜集能识别用户身份的个人信息以便TUI88可以在必要时联系用户，或为用户提供更好的使用体验。TUI88搜集的信息包括但不限于用户的姓名、性别、年龄、出生日期、身份证号、地址、学校情况、公司情况、所属行业；TUI88同意对这些信息的使用将受限于第三条用户个人隐私信息保护的约束。<br><br>
                二、用户个人隐私信息保护<br>
                &emsp;&emsp;1、用户在注册账号或使用本服务的过程中，可能需要填写或提交一些必要的信息，如法律法规、规章规范性文件（以下称“法律法规”）规定的需要填写的身份信息。如用户提交的信息不完整或不符合法律法规的规定，则用户可能无法使用本服务或在使用本服务的过程中受到限制。<br>
                &emsp;&emsp;2、个人隐私信息是指涉及用户个人身份或个人隐私的信息，比如，用户真实姓名、手机号码、邮箱、企业信息、手机设备识别码、IP地址。非个人隐私信息是指用户对本服务的操作状态以及使用习惯等明确且客观反映在TUI88服务器端的基本记录信息、个人隐私信息范围外的其它普通信息，以及用户同意公开的上述隐私信息。<br>
                &emsp;&emsp;3、尊重用户个人隐私信息的私有性是TUI88的一贯制度，TUI88将采取技术措施和其他必要措施，确保用户个人隐私信息安全，防止在本服务中收集的用户个人隐私信息泄露、毁损或丢失。在发生前述情形或者TUI88发现存在发生前述情形的可能时，将及时采取补救措施。<br>
                &emsp;&emsp;4、TUI88未经用户同意不向任何第三方公开、 透露用户个人隐私信息。但以下特定情形除外：<br>
                &emsp;&emsp;&emsp;&emsp;(1) TUI88根据法律法规规定或有权机关的指示提供用户的个人隐私信息；<br>
                &emsp;&emsp;&emsp;&emsp;(2) 由于用户将其用户密码告知他人或与他人共享注册帐户与密码，由此导致的任何个人信息的泄漏，或其他非因TUI88原因导致的个人隐私信息的泄露；<br>
                &emsp;&emsp;&emsp;&emsp;(3) 用户自行向第三方公开其个人隐私信息；<br>
                &emsp;&emsp;&emsp;&emsp;(4) 用户与TUI88及合作单位之间就用户个人隐私信息的使用公开达成约定，TUI88因此向合作单位公开用户个人隐私信息；<br>
                &emsp;&emsp;&emsp;&emsp;(5) 任何由于黑客攻击、电脑病毒侵入及其他不可抗力事件导致用户个人隐私信息的泄露。<br>
                &emsp;&emsp;5、用户同意TUI88可在以下事项中使用用户的个人隐私信息：<br>
                &emsp;&emsp;&emsp;&emsp;(1) TUI88向用户及时发送重要通知，如软件更新、本协议条款的变更；<br>
                &emsp;&emsp;&emsp;&emsp;(2) TUI88内部进行审计、数据分析和研究等，以改进TUI88的产品、服务和与用户之间的沟通；<br>
                &emsp;&emsp;&emsp;&emsp;(3) 依本协议约定，TUI88管理、审查用户信息及进行处理措施；<br>
                &emsp;&emsp;&emsp;&emsp;(4) 适用法律法规规定的其他事项。<br>
                除上述事项外，如未取得用户事先同意，TUI88不会将用户个人隐私信息使用于任何其他用途。<br>
                &emsp;&emsp;6、使用某些TUI88产品时，用户需要先行注册，并同意系统向您出示的本协议内容。如果您一但同意本协议，并完成注册激活，则表示您同意TUI88可以从TUI88所提供的系统服务中，收集您的操作行为数据。这些数据存在目的是用于帮助分析用户如何使用此应用程序，让TUI88向用户进一步提供更优质的产品服务体验。TUI88拥有向第三方提供用户的非个人隐私信息。<br><br>
                三、内容规范<br>
                &emsp;&emsp;1、本条所述内容是指用户使用本服务过程中所制作、上载、复制、发布、传播的任何内容，包括但不限于账号名称、用户说明等注册信息及认证资料，或文字、语音、图片、视频、图文等发送、回复或自动回复消息和相关链接页面，以及其他使用账号或本服务所产生的内容。<br>
                &emsp;&emsp;2、用户不得利用“TUI88”账号或本服务制作、上载、复制、发布、传播如下法律、法规和政策禁止的内容：<br>
                &emsp;&emsp;&emsp;&emsp;(1) 反对宪法所确定的基本原则的；<br>
                &emsp;&emsp;&emsp;&emsp;(2) 危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；<br>
                &emsp;&emsp;&emsp;&emsp;(3) 损害国家荣誉和利益的；<br>
                &emsp;&emsp;&emsp;&emsp;(4) 煽动民族仇恨、民族歧视，破坏民族团结的；<br>
                &emsp;&emsp;&emsp;&emsp;(5) 破坏国家宗教政策，宣扬邪教和封建迷信的；<br>
                &emsp;&emsp;&emsp;&emsp;(6) 散布谣言，扰乱社会秩序，破坏社会稳定的；<br>
                &emsp;&emsp;&emsp;&emsp;(7) 散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；<br>
                &emsp;&emsp;&emsp;&emsp;(8) 侮辱或者诽谤他人，侵害他人合法权益的；<br>
                &emsp;&emsp;&emsp;&emsp;(9) 含有法律、行政法规禁止的其他内容的信息。<br>
                &emsp;&emsp;3、用户不得利用“TUI88”账号或本服务制作、上载、复制、发布、传播如下干扰“TUI88”正常运营，以及侵犯其他用户或第三方合法权益的内容：<br>
                &emsp;&emsp;&emsp;&emsp;(1) 含有任何性或性暗示的；<br>
                &emsp;&emsp;&emsp;&emsp;(2) 含有辱骂、恐吓、威胁内容的；<br>
                &emsp;&emsp;&emsp;&emsp;(3) 含有骚扰、垃圾广告、恶意信息、诱骗信息的；<br>
                &emsp;&emsp;&emsp;&emsp;(4) 涉及他人隐私、个人信息或资料的；<br>
                &emsp;&emsp;&emsp;&emsp;(5) 侵害他人名誉权、肖像权、知识产权、商业秘密等合法权利的；<br>
                &emsp;&emsp;&emsp;&emsp;(6) 含有其他干扰本服务正常运营和侵犯其他用户或第三方合法权益内容的信息。<br><br>
                四、使用规则<br>
                &emsp;&emsp;1、用户在本服务中或通过本服务所传送、发布的任何内容并不反映或代表，也不得被视为反映或代表TUI88的观点、立场或政策，TUI88对此不承担任何责任。<br>
                &emsp;&emsp;2、用户不得利用“TUI88”账号或本服务进行如下行为：<br>
                &emsp;&emsp;&emsp;&emsp;(1) 提交、发布虚假信息，或盗用他人头像或资料，冒充、利用他人名义的；<br>
                &emsp;&emsp;&emsp;&emsp;(2) 利用技术手段批量建立虚假账号的；<br>
                &emsp;&emsp;&emsp;&emsp;(3) 利用“TUI88”账号或本服务从事任何违法犯罪活动的；<br>
                &emsp;&emsp;&emsp;&emsp;(4) 制作、发布与以上行为相关的方法、工具，或对此类方法、工具进行运营或传播，无论这些行为是否为商业目的；<br>
                &emsp;&emsp;&emsp;&emsp;(5) 其他违反法律法规规定、侵犯其他用户合法权益、干扰“TUI88”正常运营或TUI88未明示授权的行为。<br>
                &emsp;&emsp;3、用户须对利用“TUI88”账号或本服务传送信息的真实性、合法性、无害性、准确性、有效性等全权负责，与用户所传播的信息相关的任何法律责任由用户自行承担，与TUI88无关。如因此给TUI88或第三方造成损害的，用户应当依法予以赔偿。<br><br>
                五、帐户管理<br>
                &emsp;&emsp;1、 “TUI88”账号的所有权归TUI88所有，用户完成申请注册手续后，获得“TUI88”账号的使用权，该使用权仅属于初始申请注册人，禁止赠与、借用、租用、转让或售卖。TUI88因经营需要，有权回收用户的“TUI88”账号。<br>
                &emsp;&emsp;2、用户可以更改、删除“TUI88”帐户上的个人资料、注册信息及传送内容等，但需注意，删除有关信息的同时也会删除用户储存在系统中的文字和图片。用户需承担该风险。<br>
                &emsp;&emsp;3、用户有责任妥善保管注册账号信息及账号密码的安全，因用户保管不善可能导致遭受盗号或密码失窃，责任由用户自行承担。用户需要对注册账号以及密码下的行为承担法律责任。用户同意在任何情况下不使用其他用户的账号或密码。在用户怀疑他人使用其账号或密码时，用户同意立即通知TUI88。<br>
                &emsp;&emsp;4、用户应遵守本协议的各项条款，正确、适当地使用本服务，如因用户违反本协议中的任何条款，TUI88在通知用户后有权依据协议中断或终止对违约用户“TUI88”账号提供服务。同时，TUI88保留在任何时候收回“TUI88”账号、用户名的权利。<br><br>
                六、数据储存<br>
                &emsp;&emsp;1、TUI88不对用户在本服务中相关数据的删除或储存失败负责。<br>
                &emsp;&emsp;2、TUI88可以根据实际情况自行决定用户在本服务中数据的最长储存期限，并在服务器上为其分配数据最大存储空间等。用户可根据自己的需要自行备份本服务中的相关数据。<br>
                &emsp;&emsp;3、如用户停止使用本服务或本服务终止，TUI88可以从服务器上永久地删除用户的数据。本服务停止、终止后，TUI88没有义务向用户返还任何数据。<br><br>
                七、风险承担<br>
                &emsp;&emsp;1、用户理解并同意，“TUI88”仅为用户提供信息分享、传送及获取的平台，用户必须为自己注册账号下的一切行为负责，包括用户所传送的任何内容以及由此产生的任何后果。用户应对“TUI88”及本服务中的内容自行加以判断，并承担因使用内容而引起的所有风险，包括因对内容的正确性、完整性或实用性的依赖而产生的风险。TUI88无法且不会对因用户行为而导致的任何损失或损害承担责任。<br>
                如果用户发现任何人违反本协议约定或以其他不当的方式使用本服务，请立即向TUI88举报或投诉，TUI88将依本协议约定进行处理。<br>
                &emsp;&emsp;2、用户理解并同意，因业务发展需要，TUI88保留单方面对本服务的全部或部分服务内容变更、暂停、终止或撤销的权利，用户需承担此风险。<br><br>
                八、知识产权声明<br>
                &emsp;&emsp;1、除本服务中涉及广告的知识产权由相应广告商享有外，TUI88在本服务中提供的内容（包括但不限于网页、文字、图片、音频、视频、图表等）的知识产权均归TUI88所有，但用户在使用本服务前对自己发布的内容已合法取得知识产权的除外。<br>
                &emsp;&emsp;2、除另有特别声明外，TUI88提供本服务时所依托软件的著作权、专利权及其他知识产权均归TUI88所有。<br>
                &emsp;&emsp;3、TUI88在本服务中所涉及的图形、文字或其组成，以及其他TUI88标志及产品、服务名称（以下统称“TUI88标识”），其著作权或商标权归TUI88所有。未经TUI88事先书面同意，用户不得将TUI88标识以任何方式展示或使用或作其他处理，也不得向他人表明用户有权展示、使用、或其他有权处理TUI88标识的行为。<br>
                &emsp;&emsp;4、上述及其他任何TUI88或相关广告商依法拥有的知识产权均受到法律保护，未经TUI88或相关广告商书面许可，用户不得以任何形式进行使用或创造相关衍生作品。<br><br>
                九、不可抗力及其他免责事由 <br>
                &emsp;&emsp;1、用户理解并确认，在使用本服务的过程中，可能会遇到不可抗力等风险因素，使本服务发生中断。不可抗力是指不能预见、不能克服并不能避免且对一方或双方造成重大影响的客观事件，包括但不限于自然灾害如洪水、地震、瘟疫流行和风暴等以及社会事件如战争、动乱、政府行为等。出现上述情况时，TUI88将努力在第一时间与相关单位配合，及时进行修复，但是由此给用户或第三方造成的损失，TUI88及合作单位在法律允许的范围内免责。<br>
                &emsp;&emsp;2、本服务同大多数互联网服务一样，受包括但不限于用户原因、网络服务质量、社会环境等因素的差异影响，可能受到各种安全问题的侵扰，如他人利用用户的资料，造成现实生活中的骚扰；用户下载安装的其它软件或访问的其他网站中含有“特洛伊木马”等病毒，威胁到用户的计算机信息和数据的安全，继而影响本服务的正常使用等等。用户应加强信息安全及使用者资料的保护意识，要注意加强密码保护，以免遭致损失和骚扰。<br>
                &emsp;&emsp;3、用户理解并确认，本服务存在因不可抗力、计算机病毒或黑客攻击、系统不稳定、用户所在位置、用户关机以及其他任何技术、互联网络、通信线路原因等造成的服务中断或不能满足用户要求的风险，因此导致的用户或第三方任何损失，TUI88不承担任何责任。<br>
                &emsp;&emsp;4、用户理解并确认，在使用本服务过程中存在来自任何他人的包括误导性的、欺骗性的、威胁性的、诽谤性的、令人反感的或非法的信息，或侵犯他人权利的匿名或冒名的信息，以及伴随该等信息的行为，因此导致的用户或第三方的任何损失，TUI88不承担任何责任。<br>
                &emsp;&emsp;5、用户理解并确认，TUI88需要定期或不定期地对“TUI88”平台或相关的设备进行检修或者维护，如因此类情况而造成服务在合理时间内的中断，TUI88无需为此承担任何责任，但TUI88应事先进行通告。<br>
                &emsp;&emsp;6、TUI88依据法律法规、本协议约定获得处理违法违规或违约内容的权利，该权利不构成TUI88的义务或承诺，TUI88不能保证及时发现违法违规或违约行为或进行相应处理。<br>
                &emsp;&emsp;7、在任何情况下，TUI88均不对任何间接性、后果性、惩罚性、偶然性、特殊性或刑罚性的损害，包括因用户使用“TUI88”或本服务而遭受的利润损失，承担责任（即使TUI88已被告知该等损失的可能性亦然）。<br><br>
                十、服务的变更、中断、终止<br>
                &emsp;&emsp;1、鉴于网络服务的特殊性，用户同意TUI88有权随时变更、中断或终止部分或全部的服务（包括收费服务）。TUI88变更、中断或终止的服务，TUI88应当在变更、中断或终止之前通知用户，并应向受影响的用户提供等值的替代性的服务；如用户不愿意接受替代性的服务，如果该用户已经向TUI88支付的TUI88币，TUI88应当按照该用户实际使用服务的情况扣除相应TUI88币之后将剩余的TUI88币退还用户的TUI88币账户中。<br>
                &emsp;&emsp;2、如发生下列任何一种情形，TUI88有权变更、中断或终止向用户提供的免费服务或收费服务，而无需对用户或任何第三方承担任何责任：<br>
                &emsp;&emsp;&emsp;&emsp;(1) 根据法律规定用户应提交真实信息，而用户提供的个人资料不真实、或与注册时信息不一致又未能提供合理证明；<br>
                &emsp;&emsp;&emsp;&emsp;(2) 用户违反相关法律法规或本协议的约定；<br>
                &emsp;&emsp;&emsp;&emsp;(3) 按照法律规定或有权机关的要求；<br>
                &emsp;&emsp;&emsp;&emsp;(4) 出于安全的原因或其他必要的情形。<br><br>
                十一、其他<br>
                &emsp;&emsp;1、本协议的效力、解释及纠纷的解决，适用于中华人民共和国法律。若用户和TUI88之间发生任何纠纷或争议，首先应友好协商解决，协商不成的，用户同意将纠纷或争议提交TUI88住所地有管辖权的人民法院管辖。<br>
                &emsp;&emsp;2、本协议的任何条款无论因何种原因无效或不具可执行性，其余条款仍有效，对双方具有约束力。<br>
            </div>
            <div class="modal-footer clearfix">
                <button class="btn-agree" data-dismiss="modal">同意并关闭</button>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>


<?php AppAsset::addScript($this,'@web/src/js/login-regist/regist.js?v=' . Yii::$app->params['static_version']); ?>

