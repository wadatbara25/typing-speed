
//#region start layouts
export interface ITypingLayout {
    mainMenuBtns : any;
    setupMenuBtns : any;
    backBtns : any;
    setuptUserName : HTMLInputElement;
    doneSetupBtn : HTMLButtonElement;
    typingPageBackBtn : HTMLHeadingElement;
    highScoreTable : HTMLTableElement;
    measuresSpans : any;
    timerSelf : HTMLDivElement,
    timerSpan : HTMLSpanElement,
    startBtn : HTMLButtonElement,
    typingPlace: HTMLDivElement,
    wordsDone : HTMLDivElement,
    wordsComming : HTMLDivElement,
    wordInput : HTMLInputElement
  }
//#endregion  

//#region setup  
export interface ISetupInit{
    language: string,
    level: string,
    timer: number,
    sound: string,
    userName : string
  }
//#endregion

//#region classes Interface
  export interface ITypingAnalysis{
    Allwords : string[],
    correctWords : number,
    wrongWords : number,
    bothWords : number,
    correctChars : number,
    accuarcy : number,
    // fillWords : () => void,
    incCorrect : () => number,
    incWrong : () => number,
    incCorrectChars : (klma :string) => number,
    calcAccuarcy : () => number
  }
  export interface ITypingMode{
    musicAllowing : boolean,
    clickAudio : HTMLAudioElement,
    correctAudio : HTMLAudioElement,
    wrondAudio : HTMLAudioElement,
    finishAudio : HTMLAudioElement,
    startTimer : number,
    currentTimer : number,
    accuarcyTimer : number,
    typeDir : string,
    dateTiemr : number,
    decTimer : () => number,
    calcAccuarcy : () => number,
    playMusic : (type : string)=> void,
    rePlayMusic : (aud : HTMLAudioElement)=> void,
    calcCircleBackground: (acc: number)=> string,
  }
//#endregion classes Interface
