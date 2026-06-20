// ========== TETRIS PIECES (BENTUK BLOK) ==========
const TETROMINOS = {
    // Bentuk I
    I: {
        shape: [
            [0, 0, 0, 0],
            [1, 1, 1, 1],
            [0, 0, 0, 0],
            [0, 0, 0, 0]
        ],
        color: '#00e5f0'
    },
    // Bentuk O
    O: {
        shape: [
            [0, 0, 0, 0],
            [0, 1, 1, 0],
            [0, 1, 1, 0],
            [0, 0, 0, 0]
        ],
        color: '#f0e000'
    },
    // Bentuk T
    T: {
        shape: [
            [0, 0, 0, 0],
            [0, 1, 0, 0],
            [1, 1, 1, 0],
            [0, 0, 0, 0]
        ],
        color: '#c000f0'
    },
    // Bentuk S
    S: {
        shape: [
            [0, 0, 0, 0],
            [0, 1, 1, 0],
            [1, 1, 0, 0],
            [0, 0, 0, 0]
        ],
        color: '#00f040'
    },
    // Bentuk Z
    Z: {
        shape: [
            [0, 0, 0, 0],
            [1, 1, 0, 0],
            [0, 1, 1, 0],
            [0, 0, 0, 0]
        ],
        color: '#f04000'
    },
    // Bentuk J
    J: {
        shape: [
            [0, 0, 0, 0],
            [1, 0, 0, 0],
            [1, 1, 1, 0],
            [0, 0, 0, 0]
        ],
        color: '#0040f0'
    },
    // Bentuk L
    L: {
        shape: [
            [0, 0, 0, 0],
            [0, 0, 1, 0],
            [1, 1, 1, 0],
            [0, 0, 0, 0]
        ],
        color: '#f0a000'
    }
};

const SHAPES = Object.keys(TETROMINOS);
const COLS = 10;
const ROWS = 20;
const CELL_SIZE = 30; // ukuran cell dalam pixel

// ========== CLASS PIECE (Blok yang jatuh) ==========
class Piece {
    constructor(shape = null) {
        if (shape) {
            this.shape = shape.shape.map(row => [...row]);
            this.color = shape.color;
        } else {
            this.randomize();
        }
        this.x = Math.floor((COLS - this.shape[0].length) / 2);
        this.y = 0;
    }

    randomize() {
        const randomShape = SHAPES[Math.floor(Math.random() * SHAPES.length)];
        const tetromino = TETROMINOS[randomShape];
        this.shape = tetromino.shape.map(row => [...row]);
        this.color = tetromino.color;
    }

    rotate() {
        // Rotasi matriks 90 derajat
        const rotated = Array(this.shape.length).fill().map(
            () => Array(this.shape[0].length).fill(0)
        );
        
        for (let i = 0; i < this.shape.length; i++) {
            for (let j = 0; j < this.shape[i].length; j++) {
                rotated[j][this.shape.length - 1 - i] = this.shape[i][j];
            }
        }
        
        return rotated;
    }

    getShape() {
        return this.shape;
    }

    getColor() {
        return this.color;
    }

    getPosition() {
        return { x: this.x, y: this.y };
    }

    setPosition(x, y) {
        this.x = x;
        this.y = y;
    }

    move(dx, dy) {
        this.x += dx;
        this.y += dy;
    }
}

// ========== CLASS BOARD (Papan permainan) ==========
class Board {
    constructor() {
        this.grid = Array(ROWS).fill().map(() => Array(COLS).fill(0));
        this.colors = Array(ROWS).fill().map(() => Array(COLS).fill(null));
    }

    // Cek apakah posisi piece valid
    isValidMove(piece, offsetX = 0, offsetY = 0) {
        const shape = piece.getShape();
        const pos = piece.getPosition();
        const newX = pos.x + offsetX;
        const newY = pos.y + offsetY;
        
        for (let i = 0; i < shape.length; i++) {
            for (let j = 0; j < shape[i].length; j++) {
                if (shape[i][j] !== 0) {
                    const boardX = newX + j;
                    const boardY = newY + i;
                    
                    if (boardX < 0 || boardX >= COLS || boardY >= ROWS || boardY < 0) {
                        return false;
                    }
                    
                    if (boardY >= 0 && this.grid[boardY][boardX] !== 0) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    // Tempatkan piece ke board
    placePiece(piece) {
        const shape = piece.getShape();
        const pos = piece.getPosition();
        const color = piece.getColor();
        
        for (let i = 0; i < shape.length; i++) {
            for (let j = 0; j < shape[i].length; j++) {
                if (shape[i][j] !== 0) {
                    const boardX = pos.x + j;
                    const boardY = pos.y + i;
                    
                    if (boardY >= 0 && boardY < ROWS && boardX >= 0 && boardX < COLS) {
                        this.grid[boardY][boardX] = 1;
                        this.colors[boardY][boardX] = color;
                    }
                }
            }
        }
        
        return this.clearLines();
    }

    // Hapus baris yang penuh dan kembalikan jumlah baris yang dihapus
    clearLines() {
        let linesCleared = 0;
        
        for (let i = ROWS - 1; i >= 0; i--) {
            let full = true;
            for (let j = 0; j < COLS; j++) {
                if (this.grid[i][j] === 0) {
                    full = false;
                    break;
                }
            }
            
            if (full) {
                // Hapus baris
                for (let k = i; k > 0; k--) {
                    this.grid[k] = [...this.grid[k-1]];
                    this.colors[k] = [...this.colors[k-1]];
                }
                this.grid[0] = Array(COLS).fill(0);
                this.colors[0] = Array(COLS).fill(null);
                linesCleared++;
                i++; // Cek baris yang sama lagi
            }
        }
        
        return linesCleared;
    }

    // Cek game over
    isGameOver(piece) {
        return !this.isValidMove(piece, 0, 0);
    }

    // Reset board
    reset() {
        this.grid = Array(ROWS).fill().map(() => Array(COLS).fill(0));
        this.colors = Array(ROWS).fill().map(() => Array(COLS).fill(null));
    }

    getGrid() {
        return this.grid;
    }

    getColors() {
        return this.colors;
    }
}

// ========== CLASS GAME (Manajemen game) ==========
class TetrisGame {
    constructor() {
        this.board = null;
        this.currentPiece = null;
        this.nextPiece = null;
        this.score = 0;
        this.lines = 0;
        this.level = 1;
        this.highScore = this.loadHighScore();
        this.isPlaying = false;
        this.isPaused = false;
        this.gameLoop = null;
        this.fallInterval = 500; // ms per fall
        
        this.canvas = document.getElementById('tetrisCanvas');
        this.ctx = this.canvas.getContext('2d');
        this.nextCanvas = document.getElementById('nextCanvas');
        this.nextCtx = this.nextCanvas.getContext('2d');
        
        this.init();
        this.bindEvents();
    }

    init() {
        this.board = new Board();
        this.currentPiece = new Piece();
        this.nextPiece = new Piece();
        this.updateUI();
        this.draw();
    }

    bindEvents() {
        // Tombol kontrol
        document.getElementById('leftBtn').addEventListener('click', () => this.moveLeft());
        document.getElementById('rightBtn').addEventListener('click', () => this.moveRight());
        document.getElementById('rotateBtn').addEventListener('click', () => this.rotate());
        document.getElementById('downBtn').addEventListener('click', () => this.moveDown());
        document.getElementById('dropBtn').addEventListener('click', () => this.hardDrop());
        document.getElementById('startBtn').addEventListener('click', () => this.startGame());
        document.getElementById('pauseBtn').addEventListener('click', () => this.togglePause());
        document.getElementById('resetBtn').addEventListener('click', () => this.resetGame());
        
        // Keyboard control
        document.addEventListener('keydown', (e) => {
            if (!this.isPlaying || this.isPaused) return;
            
            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    this.moveLeft();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    this.moveRight();
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    this.rotate();
                    break;
                case 'ArrowDown':
                    e.preventDefault();
                    this.moveDown();
                    break;
                case ' ':
                case 'Space':
                    e.preventDefault();
                    this.hardDrop();
                    break;
                case 'p':
                case 'P':
                    this.togglePause();
                    break;
            }
        });
    }

    startGame() {
        if (this.isPlaying) return;
        
        this.reset();
        this.isPlaying = true;
        this.isPaused = false;
        this.updateGameStatus('🎮 BERMAIN... 🎮', '#38ef7d');
        
        if (this.gameLoop) clearInterval(this.gameLoop);
        this.gameLoop = setInterval(() => {
            if (this.isPlaying && !this.isPaused) {
                this.fall();
            }
        }, this.fallInterval);
        
        this.draw();
    }

    togglePause() {
        if (!this.isPlaying) return;
        
        this.isPaused = !this.isPaused;
        if (this.isPaused) {
            this.updateGameStatus('⏸️ PAUSE ⏸️', '#f5576c');
        } else {
            this.updateGameStatus('🎮 BERMAIN... 🎮', '#38ef7d');
        }
        this.draw();
    }

    resetGame() {
        if (this.gameLoop) {
            clearInterval(this.gameLoop);
            this.gameLoop = null;
        }
        
        this.board.reset();
        this.currentPiece = new Piece();
        this.nextPiece = new Piece();
        this.score = 0;
        this.lines = 0;
        this.level = 1;
        this.fallInterval = 500;
        this.isPlaying = false;
        this.isPaused = false;
        
        this.updateUI();
        this.updateGameStatus('⭐ KLIK MULAI UNTUK BERMAIN ⭐', '#ffd700');
        this.draw();
    }

    reset() {
        this.board.reset();
        this.currentPiece = new Piece();
        this.nextPiece = new Piece();
        this.score = 0;
        this.lines = 0;
        this.level = 1;
        this.fallInterval = 500;
        this.updateUI();
    }

    moveLeft() {
        if (!this.isPlaying || this.isPaused) return;
        
        if (this.board.isValidMove(this.currentPiece, -1, 0)) {
            this.currentPiece.move(-1, 0);
            this.draw();
        }
    }

    moveRight() {
        if (!this.isPlaying || this.isPaused) return;
        
        if (this.board.isValidMove(this.currentPiece, 1, 0)) {
            this.currentPiece.move(1, 0);
            this.draw();
        }
    }

    moveDown() {
        if (!this.isPlaying || this.isPaused) return;
        
        if (this.board.isValidMove(this.currentPiece, 0, 1)) {
            this.currentPiece.move(0, 1);
            this.draw();
        } else {
            this.lockPiece();
        }
    }

    rotate() {
        if (!this.isPlaying || this.isPaused) return;
        
        const originalShape = this.currentPiece.shape;
        const rotated = this.currentPiece.rotate();
        const originalShapeBackup = this.currentPiece.shape;
        
        this.currentPiece.shape = rotated;
        
        if (!this.board.isValidMove(this.currentPiece, 0, 0)) {
            // Cek wall kick (geser sedikit jika mentok)
            if (this.board.isValidMove(this.currentPiece, -1, 0)) {
                this.currentPiece.move(-1, 0);
            } else if (this.board.isValidMove(this.currentPiece, 1, 0)) {
                this.currentPiece.move(1, 0);
            } else {
                this.currentPiece.shape = originalShapeBackup;
            }
        }
        
        this.draw();
    }

    hardDrop() {
        if (!this.isPlaying || this.isPaused) return;
        
        while (this.board.isValidMove(this.currentPiece, 0, 1)) {
            this.currentPiece.move(0, 1);
        }
        this.lockPiece();
    }

    fall() {
        if (!this.isPlaying || this.isPaused) return;
        
        if (this.board.isValidMove(this.currentPiece, 0, 1)) {
            this.currentPiece.move(0, 1);
            this.draw();
        } else {
            this.lockPiece();
        }
    }

    lockPiece() {
        const linesCleared = this.board.placePiece(this.currentPiece);
        
        if (linesCleared > 0) {
            this.updateScore(linesCleared);
        }
        
        // Spawn next piece
        this.currentPiece = this.nextPiece;
        this.nextPiece = new Piece();
        
        // Cek game over
        if (this.board.isGameOver(this.currentPiece)) {
            this.gameOver();
            return;
        }
        
        this.updateUI();
        this.draw();
    }

    updateScore(linesCleared) {
        // Sistem scoring Tetris klasik
        const points = {
            1: 100,
            2: 300,
            3: 500,
            4: 800
        };
        
        const addScore = points[linesCleared] * this.level;
        this.score += addScore;
        this.lines += linesCleared;
        
        // Update level setiap 10 baris
        const newLevel = Math.floor(this.lines / 10) + 1;
        if (newLevel > this.level) {
            this.level = newLevel;
            this.fallInterval = Math.max(100, 500 - (this.level - 1) * 40);
            
            // Update interval game loop
            if (this.gameLoop) {
                clearInterval(this.gameLoop);
                this.gameLoop = setInterval(() => {
                    if (this.isPlaying && !this.isPaused) {
                        this.fall();
                    }
                }, this.fallInterval);
            }
        }
        
        // Update high score
        if (this.score > this.highScore) {
            this.highScore = this.score;
            this.saveHighScore();
        }
        
        this.updateUI();
    }

    gameOver() {
        this.isPlaying = false;
        if (this.gameLoop) {
            clearInterval(this.gameLoop);
            this.gameLoop = null;
        }
        this.updateGameStatus('💀 GAME OVER 💀', '#ff4444');
        this.draw();
    }

    draw() {
        // Draw board
        const grid = this.board.getGrid();
        const colors = this.board.getColors();
        
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        // Draw grid lines
        this.ctx.strokeStyle = '#333';
        this.ctx.lineWidth = 0.5;
        
        for (let i = 0; i <= ROWS; i++) {
            this.ctx.beginPath();
            this.ctx.moveTo(0, i * CELL_SIZE);
            this.ctx.lineTo(this.canvas.width, i * CELL_SIZE);
            this.ctx.stroke();
            
            this.ctx.beginPath();
            this.ctx.moveTo(i * CELL_SIZE, 0);
            this.ctx.lineTo(i * CELL_SIZE, this.canvas.height);
            this.ctx.stroke();
        }
        
        // Draw placed pieces
        for (let i = 0; i < ROWS; i++) {
            for (let j = 0; j < COLS; j++) {
                if (grid[i][j] !== 0) {
                    this.ctx.fillStyle = colors[i][j];
                    this.ctx.fillRect(j * CELL_SIZE, i * CELL_SIZE, CELL_SIZE - 1, CELL_SIZE - 1);
                    
                    // Add 3D effect
                    this.ctx.fillStyle = 'rgba(255,255,255,0.3)';
                    this.ctx.fillRect(j * CELL_SIZE, i * CELL_SIZE, CELL_SIZE - 1, 3);
                }
            }
        }
        
        // Draw current piece
        if (this.currentPiece) {
            const shape = this.currentPiece.getShape();
            const pos = this.currentPiece.getPosition();
            const color = this.currentPiece.getColor();
            
            for (let i = 0; i < shape.length; i++) {
                for (let j = 0; j < shape[i].length; j++) {
                    if (shape[i][j] !== 0) {
                        const x = (pos.x + j) * CELL_SIZE;
                        const y = (pos.y + i) * CELL_SIZE;
                        this.ctx.fillStyle = color;
                        this.ctx.fillRect(x, y, CELL_SIZE - 1, CELL_SIZE - 1);
                        this.ctx.fillStyle = 'rgba(255,255,255,0.3)';
                        this.ctx.fillRect(x, y, CELL_SIZE - 1, 3);
                    }
                }
            }
        }
        
        // Draw next piece
        this.nextCtx.clearRect(0, 0, this.nextCanvas.width, this.nextCanvas.height);
        if (this.nextPiece) {
            const shape = this.nextPiece.getShape();
            const color = this.nextPiece.getColor();
            const offsetX = (this.nextCanvas.width - (shape[0].length * 30)) / 2;
            const offsetY = (this.nextCanvas.height - (shape.length * 30)) / 2;
            
            for (let i = 0; i < shape.length; i++) {
                for (let j = 0; j < shape[i].length; j++) {
                    if (shape[i][j] !== 0) {
                        this.nextCtx.fillStyle = color;
                        this.nextCtx.fillRect(offsetX + j * 30, offsetY + i * 30, 28, 28);
                        this.nextCtx.fillStyle = 'rgba(255,255,255,0.3)';
                        this.nextCtx.fillRect(offsetX + j * 30, offsetY + i * 30, 28, 5);
                    }
                }
            }
        }
    }

    updateUI() {
        document.getElementById('score').textContent = this.score;
        document.getElementById('highScore').textContent = this.highScore;
        document.getElementById('level').textContent = this.level;
        document.getElementById('lines').textContent = this.lines;
    }

    updateGameStatus(message, color) {
        const statusDiv = document.getElementById('gameStatus');
        statusDiv.textContent = message;
        statusDiv.style.color = color;
    }

    loadHighScore() {
        const saved = localStorage.getItem('tetrisHighScore');
        return saved ? parseInt(saved) : 0;
    }

    saveHighScore() {
        localStorage.setItem('tetrisHighScore', this.highScore);
    }
}

// ========== START THE GAME ==========
document.addEventListener('DOMContentLoaded', () => {
    new TetrisGame();
});