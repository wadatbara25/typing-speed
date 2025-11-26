"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
//#endregion
//#region main selectors
var typingLayouts = {
    mainMenuBtns: document.querySelectorAll(".main-menu-btns button"),
    backBtns: document.querySelectorAll(".typing-app .back-btn"),
    setuptUserName: document.querySelector(".setup-typing-menu input"),
    doneSetupBtn: document.querySelector(".setup-typing-menu .done-setup-btn"),
    highScoreTable: document.querySelector(".high-scores-menu table tbody"),
    typingPageBackBtn: document.querySelector(".typing-page .back-btn"),
    setupMenuBtns: document.querySelectorAll(".setup-menu-btns button"),
    measuresSpans: document.querySelectorAll(".typing-page .measures div span:first-child"),
    startBtn: document.querySelector(".typing-info .start-btn"),
    timerSelf: document.querySelector(".typing-info .timer"),
    timerSpan: document.querySelector(".typing-info .timer span"),
    typingPlace: document.querySelector(".typing-place"),
    wordsDone: document.querySelector(".typing-place .word-done"),
    wordsComming: document.querySelector(".typing-place .coming-words"),
    wordInput: document.querySelector(".typing-place input")
};
//#endregion
//#region toggle between pages
typingLayouts.mainMenuBtns.forEach(function (btn) {
    btn.addEventListener("click", function () {
        var _a, _b;
        (_a = document
            .querySelector(".".concat(btn.dataset.layout))) === null || _a === void 0 ? void 0 : _a.classList.toggle("d-none");
        (_b = document.querySelector(".main-typing-menu")) === null || _b === void 0 ? void 0 : _b.classList.toggle("d-none");
    });
});
typingLayouts.backBtns.forEach(function (btn) {
    btn.addEventListener("click", function () {
        var _a, _b;
        (_a = btn.parentElement) === null || _a === void 0 ? void 0 : _a.classList.toggle("d-none");
        (_b = document.querySelector(".main-typing-menu")) === null || _b === void 0 ? void 0 : _b.classList.toggle("d-none");
    });
});
//#endregion
//#region setup page
var setupInit = {
    language: "english",
    level: "medium",
    timer: 60,
    sound: "on",
    userName: "HandSome"
};
typingLayouts.setupMenuBtns.forEach(function (btnClicked) {
    btnClicked.onclick = function () {
        setupInit[btnClicked.dataset.key] = btnClicked.dataset.value;
        document.querySelectorAll(".".concat(btnClicked.parentElement.classList[0], " button")).forEach(function (btn) {
            if (btn.classList.contains("active"))
                btn.classList.remove("active");
        });
        btnClicked.classList.toggle("active");
    };
});
//#endregion
//#region Analysis Class
var CTypingAnalysis = /** @class */ (function () {
    function CTypingAnalysis(Allwords, correctWords, wrongWords, bothWords, correctChars, accuarcy) {
        var _this = this;
        if (Allwords === void 0) { Allwords = []; }
        if (correctWords === void 0) { correctWords = 0; }
        if (wrongWords === void 0) { wrongWords = 0; }
        if (bothWords === void 0) { bothWords = 0; }
        if (correctChars === void 0) { correctChars = 0; }
        if (accuarcy === void 0) { accuarcy = 0; }
        this.Allwords = Allwords;
        this.correctWords = correctWords;
        this.wrongWords = wrongWords;
        this.bothWords = bothWords;
        this.correctChars = correctChars;
        this.accuarcy = accuarcy;
        this.incCorrect = function () {
            _this.correctWords++;
            _this.bothWords++;
            return _this.correctWords;
        };
        this.incWrong = function () {
            _this.wrongWords++;
            _this.bothWords++;
            return _this.wrongWords;
        };
        this.incCorrectChars = function (klma) {
            _this.correctChars += klma.length;
            return _this.correctChars;
        };
        this.calcAccuarcy = function () {
            _this.accuarcy = Math.round((_this.correctWords / _this.bothWords) * 100);
            return _this.accuarcy;
        };
    }
    ;
    return CTypingAnalysis;
}());
//#endregion
//#region Mode Class
var CTypingMode = /** @class */ (function () {
    function CTypingMode() {
        var _this = this;
        this.clickAudio = new Audio("https://ahmedsaa3d.github.io/Typing-Speed-Game/assets/audios/keyboard-spacebar-hit.mp3");
        this.correctAudio = new Audio("https://ahmedsaa3d.github.io/Typing-Speed-Game/assets/audios/correctWord.mp3");
        this.wrondAudio = new Audio("https://ahmedsaa3d.github.io/Typing-Speed-Game/assets/audios/wrongWord.mp3");
        this.finishAudio = new Audio("https://ahmedsaa3d.github.io/Typing-Speed-Game/assets/audios/completion-of-a-level.wav");
        this.accuarcyTimer = 0;
        this.decTimer = function () {
            _this.currentTimer--;
            return _this.currentTimer;
        };
        this.calcAccuarcy = function () {
            _this.accuarcyTimer = (_this.currentTimer / _this.startTimer) * 100;
            return _this.accuarcyTimer;
        };
        this.playMusic = function (type) {
            if (_this.musicAllowing) {
                type == "finish" ? _this.rePlayMusic(_this.finishAudio) :
                    type == "correct" ? _this.rePlayMusic(_this.correctAudio) :
                        type == "wrong" ? _this.rePlayMusic(_this.wrondAudio) :
                            _this.rePlayMusic(_this.clickAudio);
            }
        };
        this.rePlayMusic = function (aud) {
            aud.currentTime = 0;
            aud.play();
        };
        this.calcCircleBackground = function (acc) {
            return "radial-gradient(closest-side, #164863 89%, transparent 80% 100%), \n      conic-gradient(#427D9D ".concat(acc, "%, #DDF2FD 0)");
        };
        this.startTimer = setupInit.timer;
        this.currentTimer = this.startTimer;
        this.typeDir = setupInit.language == "arabic" ? "rtl" : "ltr";
        this.musicAllowing = setupInit.sound == "on" ? true : false;
        this.dateTiemr = new Date().getTime();
    }
    return CTypingMode;
}());
//#endregion
//#region start ininialize
var startTypingAnalysis = new CTypingAnalysis();
var startTypingMode = new CTypingMode();
var mainTimerInterval;
var firstTimePlay = true;
//#endregion
//#region Start Game
typingLayouts.mainMenuBtns[0].addEventListener("click", function () {
    makeInit();
    firstTimePlay = true;
});
typingLayouts.startBtn.onclick = function () {
    if (typingLayouts.startBtn.textContent == "Again") {
        typingLayouts.startBtn.textContent = "Start";
        firstTimePlay ? firstTimePlay = false : makeInit();
    }
    else {
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
typingLayouts.mainMenuBtns[2].addEventListener("click", function () {
    var lclStrgData = [];
    dealWithLclStrgData(lclStrgData);
    fillHighScore(lclStrgData);
});
//#endregion
//#region Plying
function startTimer() {
    mainTimerInterval = setInterval(function () {
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
function startCheck() {
    typingLayouts.wordInput.oninput = function () {
        startTypingMode.playMusic("word");
        //if typed space first remove it
        if (typingLayouts.wordInput.value[0] == " " && typingLayouts.wordInput.value.length == 1)
            typingLayouts.wordInput.value = ""; //make it empty again!
        //if entered space check
        else if (typingLayouts.wordInput.value[typingLayouts.wordInput.value.length - 1] == " ") {
            var firstSpan = document.querySelector(".typing-place .coming-words > span:first-of-type");
            var createNewSpan = document.createElement("span");
            createNewSpan.textContent = firstSpan.textContent;
            //correct
            if (typingLayouts.wordInput.value.trim() === firstSpan.innerHTML) {
                startTypingMode.playMusic("correct");
                typingLayouts.measuresSpans[0].textContent = startTypingAnalysis.incCorrect() + "";
                typingLayouts.measuresSpans[1].textContent = startTypingAnalysis.incCorrectChars(typingLayouts.wordInput.value.trim()) + "";
                createNewSpan.classList.add("correct");
            }
            else {
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
function finishTheTest() {
    startTypingMode.playMusic("finish");
    typingLayouts.wordInput.readOnly = true;
    typingLayouts.startBtn.classList.remove("disapled");
    typingLayouts.startBtn.disabled = false;
    saveLocalStorage();
}
//#endregion
//#region main helper function
function makeInit() {
    var _a;
    startTypingAnalysis = new CTypingAnalysis();
    startTypingMode = new CTypingMode();
    fillWords();
    typingLayouts.startBtn.textContent = "Start";
    typingLayouts.measuresSpans[0].textContent = 0;
    typingLayouts.measuresSpans[1].textContent = 0;
    typingLayouts.measuresSpans[2].textContent = 0;
    typingLayouts.timerSpan.innerHTML = startTypingMode.currentTimer + "";
    typingLayouts.wordInput.readOnly = true;
    (_a = typingLayouts.startBtn) === null || _a === void 0 ? void 0 : _a.classList.remove("disapled");
    typingLayouts.startBtn.disabled = false;
    typingLayouts.wordInput.value = "";
    typingLayouts.typingPlace.style.direction = startTypingMode.typeDir;
    typingLayouts.timerSelf.style.background = startTypingMode.calcCircleBackground(100);
    clearInterval(mainTimerInterval);
}
function createWordsAndAppend(shuffeldWords) {
    typingLayouts.wordsComming.innerHTML = "";
    typingLayouts.wordsDone.innerHTML = "";
    typingLayouts.wordInput.innerHTML = "";
    for (var i = 0; i < shuffeldWords.length; i++) {
        var wordSpan = document.createElement("span");
        wordSpan.textContent = shuffeldWords[i];
        typingLayouts.wordsComming.appendChild(wordSpan);
    }
}
function shuffle(words) {
    var _a;
    var curIndex = words.length;
    var ranIndex;
    // While there remain elements to shuffle.
    while (curIndex > 0) {
        // Pick a remaining element.
        ranIndex = Math.floor(Math.random() * curIndex);
        curIndex--;
        // And swap it with the current element using destruction
        _a = [words[ranIndex], words[curIndex]], words[curIndex] = _a[0], words[ranIndex] = _a[1];
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
function createLclObj(nme, words, acc, lng) {
    return {
        name: nme,
        words: words,
        acc: acc,
        lng: lng
    };
}
function fillWords() {
    var myRequset = new XMLHttpRequest();
    myRequset.open("GET", "https://ahmedsaa3d.github.io/Typing-Speed-Game/languages/".concat(setupInit.language, "/").concat(setupInit.language, "-").concat(setupInit.level, ".json"), true);
    myRequset.send();
    myRequset.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            startTypingAnalysis.Allwords = JSON.parse(this.responseText);
            startTypingAnalysis.Allwords = shuffle(startTypingAnalysis.Allwords);
            createWordsAndAppend(startTypingAnalysis.Allwords);
        }
    };
}
typingLayouts.wordInput.onpaste = function () {
    return false;
};
typingLayouts.typingPageBackBtn.addEventListener("click", function () {
    clearInterval(mainTimerInterval);
});
typingLayouts.doneSetupBtn.addEventListener("click", function () {
    setupInit["userName"] = typingLayouts.setuptUserName.value;
});
//#endregion main helper function
//#region local storage 
function saveLocalStorage() {
    window.localStorage.setItem((startTypingMode.dateTiemr + ""), "".concat(setupInit.userName || "handSome", ",").concat(startTypingAnalysis.correctWords, ",").concat(startTypingAnalysis.accuarcy, ",").concat(setupInit.language.slice(0, 2)));
    // window.localStorage.setItem(startTypingMode.dateTiemr + "", JSON.stringify(setupInit));
}
function fillHighScore(lclStrgData) {
    typingLayouts.highScoreTable.innerHTML = "";
    for (var i = 0; i < lclStrgData.length; i++) {
        var tr = document.createElement("tr");
        tr.innerHTML = "<td>".concat(lclStrgData[i].name, "</td>\n    <td>").concat(lclStrgData[i].words, "</td>\n    <td>&nbsp;&nbsp;").concat(lclStrgData[i].acc, "&nbsp;&nbsp;</td>\n    <td>&nbsp;&nbsp;").concat(lclStrgData[i].lng, "&nbsp;&nbsp;</td>");
        typingLayouts.highScoreTable.appendChild(tr);
    }
}
function dealWithLclStrgData(lclStrgData) {
    Object.keys(window.localStorage).forEach(function (ele) {
        var splt = window.localStorage[ele].split(",");
        var lclStrgObj = createLclObj(splt[0], parseInt(splt[1]), parseInt(splt[2]), splt[3]);
        lclStrgData.push(lclStrgObj);
    });
    lclStrgData.sort(helperSortWords);
}
//#endregion 
