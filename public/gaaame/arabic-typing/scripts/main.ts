
//#region imports
import { ITypingLayout, ITypingAnalysis,ISetupInit,ITypingMode   } from "./scriptsHelper/interface.model";
//#endregion

//#region main selectors
const typingLayouts : ITypingLayout = {
  mainMenuBtns : document.querySelectorAll(".main-menu-btns button"),
  backBtns : document.querySelectorAll(".typing-app .back-btn") as NodeList,
  setuptUserName: document.querySelector(".setup-typing-menu input") as HTMLInputElement,  
  doneSetupBtn: document.querySelector(".setup-typing-menu .done-setup-btn") as HTMLButtonElement,  
  highScoreTable : document.querySelector(".high-scores-menu table tbody") as HTMLTableElement,
  typingPageBackBtn :document.querySelector(".typing-page .back-btn") as HTMLHeadingElement,
  setupMenuBtns : document.querySelectorAll(".setup-menu-btns button") as NodeList,
  measuresSpans : document.querySelectorAll(".typing-page .measures div span:first-child") as NodeList,
  startBtn : document.querySelector(".typing-info .start-btn") as HTMLButtonElement,
  timerSelf : document.querySelector(".typing-info .timer") as HTMLDivElement,
  timerSpan : document.querySelector(".typing-info .timer span") as HTMLSpanElement,
  typingPlace : document.querySelector(".typing-place") as HTMLDivElement,
  wordsDone : document.querySelector(".typing-place .word-done") as HTMLDivElement,
  wordsComming: document.querySelector(".typing-place .coming-words") as HTMLDivElement,
  wordInput: document.querySelector(".typing-place input") as HTMLInputElement
};
//#endregion

//#region toggle between pages
typingLayouts.mainMenuBtns.forEach((btn : any)=>{
  btn.addEventListener("click", function () {
    document
      .querySelector(`.${btn.dataset.layout}`)
      ?.classList.toggle("d-none");
    document.querySelector(".main-typing-menu")?.classList.toggle("d-none");
  });
});

typingLayouts.backBtns.forEach((btn:any)=>{
  btn.addEventListener("click", function () {
    btn.parentElement?.classList.toggle("d-none");
    document.querySelector(".main-typing-menu")?.classList.toggle("d-none");    
  });
})
//#endregion

//#region setup page
let setupInit: ISetupInit = {
  language : "english",
  level : "medium",
  timer : 60,
  sound : "on",
  userName : "HandSome"
}
typingLayouts.setupMenuBtns.forEach((btnClicked)=>{
  btnClicked.onclick = ()=>{
    setupInit[btnClicked.dataset.key] = btnClicked.dataset.value;
    document.querySelectorAll(`.${btnClicked.parentElement.classList[0]} button`).forEach((btn)=>{
      if(btn.classList.contains("active"))
          btn.classList.remove("active");
    })
    btnClicked.classList.toggle("active");
  }
})
//#endregion

//#region Analysis Class
class CTypingAnalysis implements ITypingAnalysis {
  constructor(public Allwords: string[] = [] ,public correctWords:number = 0, public wrongWords:number = 0, public bothWords:number = 0, public correctChars: number = 0, public accuarcy: number = 0){};
  incCorrect = ()=>{
    this.correctWords++;
    this.bothWords++;
    return this.correctWords;
  };
  incWrong = ()=>{
    this.wrongWords++;
    this.bothWords++;
    return this.wrongWords;
  };
  incCorrectChars = (klma : string)=>{
    this.correctChars += klma.length;
    return this.correctChars;
  };
  calcAccuarcy = ()=>{
    this.accuarcy = Math.round((this.correctWords / this.bothWords) * 100)
    return this.accuarcy;
  }
}
//#endregion

//#region Mode Class
class CTypingMode implements ITypingMode{
  musicAllowing: boolean;
  clickAudio: HTMLAudioElement = new Audio("https://ahmedsaa3d.github.io/Typing-Speed-Game/assets/audios/keyboard-spacebar-hit.mp3");
  correctAudio: HTMLAudioElement = new Audio("https://ahmedsaa3d.github.io/Typing-Speed-Game/assets/audios/correctWord.mp3");
  wrondAudio: HTMLAudioElement = new Audio("https://ahmedsaa3d.github.io/Typing-Speed-Game/assets/audios/wrongWord.mp3");
  finishAudio: HTMLAudioElement = new Audio("https://ahmedsaa3d.github.io/Typing-Speed-Game/assets/audios/completion-of-a-level.wav");
  startTimer: number
  currentTimer: number;
  accuarcyTimer: number = 0;
  typeDir: string; 
  dateTiemr: number;
  constructor(){
    this.startTimer = setupInit.timer;
    this.currentTimer = this.startTimer;
    this.typeDir = setupInit.language == "arabic" ? "rtl" : "ltr";
    this.musicAllowing = setupInit.sound == "on" ? true : false;
    this.dateTiemr = new Date().getTime();
  }
  decTimer = ():number =>{
    this.currentTimer--;
    return this.currentTimer;
  }
  calcAccuarcy = ():number => {
    this.accuarcyTimer = (this.currentTimer / this.startTimer) * 100;
    return this.accuarcyTimer;
  }
  playMusic = (type: string):void => {
    if(this.musicAllowing){
      type == "finish" ? this.rePlayMusic(this.finishAudio) :
      type == "correct" ? this.rePlayMusic(this.correctAudio):
      type == "wrong" ? this.rePlayMusic(this.wrondAudio):
      this.rePlayMusic(this.clickAudio);
    }
  }
  rePlayMusic = (aud: HTMLAudioElement):void => {
    aud.currentTime = 0;
    aud.play();
  }
  calcCircleBackground = (acc: number):string =>{
    return `radial-gradient(closest-side, #164863 89%, transparent 80% 100%), 
      conic-gradient(#427D9D ${acc}%, #DDF2FD 0)`;
  }
}
//#endregion

//#region start ininialize
let startTypingAnalysis = new CTypingAnalysis(); 
let startTypingMode = new CTypingMode();
let mainTimerInterval;
let firstTimePlay:boolean = true;
//#endregion

//#region Start Game
typingLayouts.mainMenuBtns[0].addEventListener("click",function(){
    makeInit();
    firstTimePlay = true;
})

typingLayouts.startBtn.onclick = function () : void {
    if(typingLayouts.startBtn.textContent == "Again"){
      typingLayouts.startBtn.textContent = "Start";
      firstTimePlay ? firstTimePlay = false : makeInit();
    }
    else{
      firstTimePlay = false;
      typingLayouts.startBtn.textContent = "Again";      
      typingLayouts.startBtn.disabled = true;
      typingLayouts.startBtn.classList.add("disapled");
      typingLayouts.wordInput.readOnly = false;
      typingLayouts.wordInput.focus();
      startTimer();
      startCheck();
    } 
};

typingLayouts.mainMenuBtns[2].addEventListener("click", function(){
  let lclStrgData : object[] = [];
  dealWithLclStrgData(lclStrgData);
  fillHighScore(lclStrgData);
})
//#endregion

//#region Plying

function startTimer() : void{
  mainTimerInterval = setInterval(() => {
  startTypingMode.decTimer();
  startTypingMode.calcAccuarcy();
  typingLayouts.timerSelf.style.background = startTypingMode.calcCircleBackground(startTypingMode.accuarcyTimer);

  typingLayouts.timerSpan.innerHTML = startTypingMode.currentTimer + "";
  if (startTypingMode.currentTimer == 0) {
    clearInterval(mainTimerInterval);
    finishTheTest();
  }
}, 1000);
}

function startCheck() : void {
typingLayouts.wordInput.oninput = function () : void {
startTypingMode.playMusic("word");  
  
  //if typed space first remove it
  if (typingLayouts.wordInput.value[0] == " " && typingLayouts.wordInput.value.length == 1) 
      typingLayouts.wordInput.value = ""; //make it empty again!
    //if entered space check
  else if (typingLayouts.wordInput.value[typingLayouts.wordInput.value.length - 1] == " ") {
    let firstSpan = document.querySelector(".typing-place .coming-words > span:first-of-type") as HTMLSpanElement;
    let createNewSpan = document.createElement("span");
    createNewSpan.textContent = firstSpan.textContent;

    //correct
    if (typingLayouts.wordInput.value.trim() === firstSpan.innerHTML) {
      startTypingMode.playMusic("correct");  
      
      typingLayouts.measuresSpans[0].textContent = startTypingAnalysis.incCorrect() + "";
      typingLayouts.measuresSpans[1].textContent = startTypingAnalysis.incCorrectChars(typingLayouts.wordInput.value.trim()) + "";
      
      createNewSpan.classList.add("correct");

    } else {
      startTypingMode.playMusic("wrong");  
      
      startTypingAnalysis.incWrong();
      
      createNewSpan.classList.add("wrong");
    }
    firstSpan.remove();
    typingLayouts.wordsDone.appendChild(createNewSpan);
    typingLayouts.measuresSpans[2].textContent = startTypingAnalysis.calcAccuarcy() + "";
    typingLayouts.wordInput.value = ""; //make it empty again!
  }
};
}

function finishTheTest() : void {
startTypingMode.playMusic("finish");
typingLayouts.wordInput.readOnly = true;
typingLayouts.startBtn.classList.remove("disapled");
typingLayouts.startBtn.disabled = false;
saveLocalStorage();
}
//#endregion

//#region main helper function
function makeInit() :void{
  startTypingAnalysis = new CTypingAnalysis(); 
  startTypingMode = new CTypingMode();
  fillWords();
  typingLayouts.startBtn.textContent = "Start";    
  typingLayouts.measuresSpans[0].textContent = 0;
  typingLayouts.measuresSpans[1].textContent = 0;
  typingLayouts.measuresSpans[2].textContent = 0;
  typingLayouts.timerSpan.innerHTML = startTypingMode.currentTimer + "";
  typingLayouts.wordInput.readOnly = true;
  typingLayouts.startBtn?.classList.remove("disapled");
  typingLayouts.startBtn.disabled = false;
  typingLayouts.wordInput.value = "";
  typingLayouts.typingPlace.style.direction = startTypingMode.typeDir;
  typingLayouts.timerSelf.style.background = startTypingMode.calcCircleBackground(100);
  clearInterval(mainTimerInterval);
}
function createWordsAndAppend(shuffeldWords: string[]) {
  typingLayouts.wordsComming.innerHTML = "";
  typingLayouts.wordsDone.innerHTML = "";
  typingLayouts.wordInput.innerHTML = "";
  for (let i = 0; i < shuffeldWords.length; i++) {
    let wordSpan = document.createElement("span");
    wordSpan.textContent = shuffeldWords[i];
    typingLayouts.wordsComming.appendChild(wordSpan);
  }
}
function shuffle(words : string[]) : string[] {
  let curIndex : number = words.length;
  let ranIndex : number;
  // While there remain elements to shuffle.
  while (curIndex > 0) {
    // Pick a remaining element.
    ranIndex = Math.floor(Math.random() * curIndex);
    curIndex--;
    // And swap it with the current element using destruction
    [words[curIndex], words[ranIndex]] = [words[ranIndex],words[curIndex]];
  }
  return words;
}
function helperSortWords(a, b) {
  return a.words < b.words
    ? 1
    : a.words > b.words
    ? -1
    : a.acc < b.acc
    ? 1
    : a.acc > b.acc
    ? -1
    : 0;
}
function createLclObj(nme:string, words: number, acc: number, lng:string){
  return{
    name : nme,
    words : words,
    acc : acc,
    lng : lng
  }
}
function fillWords() : void{
  let myRequset : XMLHttpRequest = new XMLHttpRequest();
  myRequset.open("GET", `https://ahmedsaa3d.github.io/Typing-Speed-Game/languages/${setupInit.language}/${setupInit.language}-${setupInit.level}.json`, true);
  myRequset.send();
  myRequset.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      startTypingAnalysis.Allwords = JSON.parse(this.responseText);
      
      startTypingAnalysis.Allwords = shuffle(startTypingAnalysis.Allwords);
      createWordsAndAppend(startTypingAnalysis.Allwords);
    }
  };  
}
typingLayouts.wordInput.onpaste = function () : boolean {
  return false;
};
typingLayouts.typingPageBackBtn.addEventListener("click", function () {
  clearInterval(mainTimerInterval);  
});
typingLayouts.doneSetupBtn.addEventListener("click",function(){
  setupInit["userName"] = typingLayouts.setuptUserName.value;
});
//#endregion main helper function

//#region local storage 
function saveLocalStorage(){
  window.localStorage.setItem((startTypingMode.dateTiemr + ""), 
  `${setupInit.userName || "handSome"},${startTypingAnalysis.correctWords},${startTypingAnalysis.accuarcy},${setupInit.language.slice(0,2)}`);
  // window.localStorage.setItem(startTypingMode.dateTiemr + "", JSON.stringify(setupInit));
}

function fillHighScore(lclStrgData){
  typingLayouts.highScoreTable.innerHTML = "";
  for(let i=0;i<lclStrgData.length;i++){
    let tr = document.createElement("tr");
    tr.innerHTML = `<td>${lclStrgData[i].name}</td>
    <td>${lclStrgData[i].words}</td>
    <td>&nbsp;&nbsp;${lclStrgData[i].acc}&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;${lclStrgData[i].lng}&nbsp;&nbsp;</td>`;
    typingLayouts.highScoreTable.appendChild(tr);
  }
}

function dealWithLclStrgData(lclStrgData : object[]) {
  Object.keys(window.localStorage).forEach(function (ele) {
    var splt = window.localStorage[ele].split(",");
    var lclStrgObj = createLclObj(
      splt[0],
      parseInt(splt[1]),
      parseInt(splt[2]),
      splt[3]
    );
    lclStrgData.push(lclStrgObj);
  });
  lclStrgData.sort(helperSortWords);
}
//#endregion 
