const loader = PIXI.Loader.shared;
const soundTicker = new PIXI.Ticker();
let animTicker;
let app;
let canvas;
let background;
const adjustFilter = new PIXI.filters.AdjustmentFilter();
const blurFilter = new PIXI.filters.BlurFilter(0, 2);
blurFilter.repeatEdgePixels = true;
var soundManager;

const effectsContainer = new PIXI.Container();
let fireworkAnimation;
let numMortars = 10;
let explosion = [];
let tints = [0x6aff80, 0x38a2ff, 0xe42f44, 0xf8e13b];
const particleMaxForce = 12.7;
const AnimStates = {
  GROWINGWAIT: 1,
  SHRINKINGWAIT: 2,
  TAPPED: 3,
  SHRINKFINISH: 4,
  EXPLODE: 5,
  EXPLODE2: 6,
  RUNEDONE: -1
};
const TAU = Math.PI * 2;
const runeParticleCount = 120;
let ww = 0;
let wh = 0;

try {
  ww = Math.round(window.visualViewport.width * window.visualViewport.scale); // Only Chrome implements this new standard
  wh = Math.round(window.visualViewport.height * window.visualViewport.scale);
} catch (err) {
  ww = window.innerWidth;
  wh = window.innerHeight;
}

loader
  .add(
    "Level1",
    "images/trans.jpg"
  )
  .add(
    "whitedot",
    "https://nextyoustorage.blob.core.windows.net/nextyou/whitedot.png"
  )
  .load(function () {
    PixiLoaderReady();
  });

function PixiLoaderReady() {
  canvas = document.getElementById("mycanvas");
  app = new PIXI.Application({
    view: canvas,
    width: ww,
    height: wh,
    antialias: true,
    backgroundAlpha: 0,
    autoResize: true,
    resolution: window.devicePixelRatio || 1,
    autoDensity: true
  });
  SetBackground("Level1");

  // StartFireworks();
}

function SetBackground(bgName) {
  app.stage.removeChild(background);
  background = new PIXI.Sprite(loader.resources[bgName].texture);
  // Scale the picture to the width (height will always be too tall)
  let height = 2048; //    wh  // ww * 1.7777 // The aspect ratio of the original art
  let width = 1153;
  let scale = 1.0;
  let offsetX = 0;
  let offsetY = 0;
  if (height > wh) {
    let heightRatio = height / wh;
    let widthRatio = width / ww;
    if (heightRatio > widthRatio) {
      scale = 1 / widthRatio;
      width = width * scale;
      height = height * scale;
      offsetY = (height - wh) / 2;
    } else {
      scale = 1 / heightRatio;
      width = width * scale;
      height = height * scale;
      offsetX = (width - ww) / 2;
    }
  }
  background.y = 0 - offsetY;
  background.x = 0 - offsetX;
  background.width = width;
  background.height = height;
  background.filters = [blurFilter, adjustFilter];
  app.stage.addChild(background);
  app.stage.addChild(effectsContainer);
}

function StartFireworks() {
//   gsap.to(blurFilter, 1, { blur: 7 });
//   gsap.to(adjustFilter, 1, { brightness: 0.3 });
fireworkAnimation = new FireworkAnimationManager();
  fireworkAnimation.PlayFireworks();
}

class FireworkAnimationManager {
  constructor() {
    this.IsAnimating = false;
  }
  PlayFireworks() {
    setTimeout(() => {
      for (let j = 0; j < numMortars; j++) {
        setTimeout(() => {
          let xValue = random(0.05, 0.95) * ww;
          let yEndingValue = random(50, 400);
          let tintIdx = randomInt(0, 4);
          explosion[j] = new Explosion(xValue, yEndingValue, tints[tintIdx]);
          explosion[j].InitExplosion();
        }, 1000 * j);
      }
    }, 1000);
    this.IsAnimating = true;
    animTicker = new PIXI.Ticker();
    animTicker.add(() => {
      for (let i = 0; i < explosion.length; i++) {
        if (explosion[i] && explosion[i].glowSpriteAnimating)
          explosion[i].Update();
      }
    });
    animTicker.start();
    setTimeout(() => {
      animTicker.stop();
      animTicker.destroy();
      for (let i = explosion.length - 1; i >= 0; i--) {
        if (explosion[i]) explosion[i].CleanUp();
      }
      explosion = [];
    }, 60000); // Long enough that animations on even slow devices should be over
  }
}

class Explosion {
  constructor(x, y, tint) {
    this.glowSprite = null;
    this.x = x;
    this.y = y;
    this.runeParticles = [];
    this.startTime = 0; //window.performance.now
    this.glowSpriteAnimating = false;
    this.glowAnimState = AnimStates.RUNEDONE;
    this.tint = tint;
  }
  InitExplosion() {
    for (let i = 0; i < runeParticleCount; i++) {
      this.runeParticles.push(new ExplosionParticle(this.tint));
    }
    this.glowSprite = new PIXI.Sprite(loader.resources["whitedot"].texture);
    this.glowSprite.anchor.set(0.5, 0.5);
    this.glowSprite.x = this.x;
    this.glowSprite.y = this.y;
    this.glowSprite.tint = this.tint;
    this.glowSprite.width = 25;
    this.glowSprite.height = 25;
    this.glowSprite.alpha = 0.2;
    effectsContainer.addChild(this.glowSprite);
    this.glowAnimState = AnimStates.EXPLODE;
    this.glowSpriteAnimating = true;
  }
  Update() {
    for (var i = 0; i < runeParticleCount; i++) {
      if (this.runeParticles[i].isInit) {
        if (this.runeParticles[i].alive)
          this.runeParticles[i].Update(
            window.performance.now() - this.startTime
          );
      }
    }
    if (this.glowSpriteAnimating) {
      if (this.glowAnimState === AnimStates.EXPLODE) {
        this.glowSprite.width += 6;
        this.glowSprite.height += 6;
        if (this.glowSprite.alpha < 1) this.glowSprite.alpha += 0.05;
        if (this.glowSprite.width > 200) {
          this.glowAnimState = AnimStates.EXPLODE2;
        }
      } else if (this.glowAnimState === AnimStates.EXPLODE2) {
        this.startTime = window.performance.now();
        this.glowAnimState = AnimStates.EXPLODE3;
        //soundManager.PlaySound("fireworks");
        for (let i = 0; i < runeParticleCount; i++) {
          setTimeout(() => {
            this.runeParticles[i].Init(this.x, this.y);
          }, random(0.2, 1.2));
        }
      } else if (this.glowAnimState === AnimStates.EXPLODE3) {
        this.glowSprite.alpha -= 0.05;
        if (this.glowSprite.alpha < 0.2) {
          this.glowAnimState = AnimStates.RUNEDONE;
          this.glowSprite.parent.removeChild(this.glowSprite);
        }
      }
    }
  }
  CleanUp() {
    for (let i = 0; i < runeParticleCount; i++) {
      this.runeParticles[i].texture.destroy();
      delete this.runeParticles[i];
    }
    this.glowSprite.texture.destroy();
  }
}

class ExplosionParticle {
  constructor(tint) {
    this.texture = new PIXI.Sprite(loader.resources["whitedot"].texture);
    this.texture.anchor.set(0.5, 0.5);
    this.texture.tint = tint;
    this.alive = false;
    this.isInit = false;
    this.angle = random(0, TAU);
    this.force = 0;
    this.gravity = 0.5;
    this.size = random(20, 52); // randomize particle size
    this.originalSize = this.size;
    this.alpha = 0;
    this.theta = this.angle;
    this.vx = Math.sin(this.angle);
    this.vy = Math.cos(this.angle);
    this.drag = random(0.82, 0.97);
    this.wander = random(0.5, 1.0);
    this.texture.width = this.size;
    this.texture.height = this.size;
    this.texture.alpha = this.alpha;
    effectsContainer.addChild(this.texture);
  }
  Init(x, y) {
    this.x = x;
    this.y = y;
    this.originX = x;
    this.originY = y;
    this.texture.x = this.x;
    this.texture.y = this.y;
    this.size = this.originalSize;
    this.texture.width = this.originalSize;
    this.texture.height = this.originalSize;
    this.alpha = 1;
    this.texture.alpha = 1;
    this.alive = true;
    this.isInit = true;
  }
  Update(deltaTime) {
    if (!this.alive) return;
    if (deltaTime > 1000) deltaTime = 1000;
    this.force = ((1000 - deltaTime) / 1000) * particleMaxForce;
    this.x += this.vx * this.force;
    this.y += this.vy * this.force + this.gravity;
    this.vx *= this.drag;
    this.vy *= this.drag;
    this.theta += random(-0.5, 0.5) * this.wander;
    this.vx += Math.sin(this.theta) * 0.01;
    this.vy += Math.cos(this.theta) * 0.01;
    //this.rotation = Math.atan2(this.vy, this.vx);
    this.alpha *= 0.999;
    this.size *= 0.99;
    this.alive = this.size > 0.6 && this.alpha > 0.1;
    if (this.alive) {
      this.texture.x = this.x;
      this.texture.y = this.y;
      this.texture.alpha = this.alpha;
      this.texture.width = this.size;
      this.texture.height = this.size;
    }
  }
}

function random(min, max) {
  if (max === null) {
    max = min;
    min = 0;
  }
  if (min > max) {
    var tmp = min;
    min = max;
    max = tmp;
  }
  return min + (max - min) * Math.random();
}

function randomInt(min, max) {
  if (max === null) {
    max = min;
    min = 0;
  }
  if (min > max) {
    var tmp = min;
    min = max;
    max = tmp;
  }
  return Math.floor(min + (max - min) * Math.random());
}