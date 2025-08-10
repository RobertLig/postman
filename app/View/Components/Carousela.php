<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Carousela extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?array $dataCarousel = null,
        public ?string $input = null,
        public ?string $totalValue = null,
        public ?string $startValue = null,
        public ?string $modelName = null,
        public ?string $isLive = "", //doesn't work with boolean (false returns null). Can't assign default value for string. String 'true' or 'false' must be explicitly set on snippet tag
        //public ?string $setPropertyMethod = null,
        public ?string $prefixZero = null, //the same problem as with $isLive; Can't be used together with $textValues
        public ?array $textValues = null, //Can't be used together with $prefixZero
        
        //slots
        public mixed $inputElement,
        public mixed $progress,
    )
    {
        //dd( $this->textValues );
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div class="" x-data="{ 
                rotateDegree: 20,
                currentDegree: 0,

                limit: 17,
                total: 18,

                input: {{ $input }}, //1-100; 1
                nodeValue: 0, //0-17; inputValue

                totalValue: {{ $totalValue }}, //100
                startValue: {{ $startValue }}, //1

                nodeList: document.querySelectorAll('#{{ $modelName }} .picker-item'),

                //inputPlaceholder: null,

                prefixZero: {{ $prefixZero }},

                textValues: @js($textValues),

                dataCarousel: @js($dataCarousel),

                //dragging
                dragStart: 0,
                finalDegreeDrag:0,
                distanceDrag: 0,

                setInput(event) {
                    if (event.deltaY < 0) { 
                        if(this.input == this.startValue) //input start from 1
                        {
                            this.input = this.totalValue;
                        }
                        else
                        {
                            this.input--;
                        }
                    } else { 
                        if(this.input == this.totalValue) //input end in 100
                        {
                            this.input = this.startValue;
                        }
                        else
                        {
                            this.input++;
                        }
                    }
                },

                prependZero(value)
                {
                    return '0' + value;
                },

                belowInput(input, node)
                {
                    if(input < this.startValue) //input start from 1; //0-(-3) max; 
                    { 
                        let belowLimit = this.startValue - input;

                        let value = this.totalValue - (belowLimit - 1);

                        if(this.prefixZero && value < 10)
                        {
                            value = this.prependZero(value);
                        }
                        else if(this.textValues)
                        {
                            value = this.textValues[value];
                        }
                                    
                        this.nodeList[node].innerHTML = value;                                      
                    }
                    else
                    {
                        if(this.prefixZero && input < 10)
                        {
                            input = this.prependZero(input);
                        }
                        else if(this.textValues)
                        {
                            input = this.textValues[input];
                        }

                        this.nodeList[node].innerHTML = input; 
                    }
                },

                aboveInput(input, node)
                {
                    if(input > this.totalValue) //input end in 100
                    {
                        let aboveLimit = input - this.totalValue;

                        let value = this.startValue + (aboveLimit - 1); //1-4 max;

                        if(this.prefixZero && value < 10)
                        {
                            value = this.prependZero(value);
                        }
                        else if(this.textValues)
                        {
                            value = this.textValues[value];
                        }

                        this.nodeList[node].innerHTML = value; 
                    }
                    else
                    {
                        if(this.prefixZero && input < 10)
                        {
                            input = this.prependZero(input);
                        }
                        else if(this.textValues)
                        {
                            input = this.textValues[input];
                        }

                        this.nodeList[node].innerHTML = input; 
                    }
                },

                setNodes() {
                    for(let i = 4; i >= 1; i--) 
                    {
                        let node = this.nodeValue - i;
                        let input = this.input - i;

                        if(node < 0) 
                        {  
                            this.belowInput(input, this.total + node);
                        }
                        else if(node >= 0) //>= ? or > ?
                        {
                            this.belowInput(input, node);
                        }
                    }

                    if(this.prefixZero && this.input < 10)
                    {
                        this.nodeList[this.nodeValue].innerHTML = this.prependZero(this.input);
                    }
                    else if(this.textValues)
                    {
                        this.nodeList[this.nodeValue].innerHTML = this.textValues[this.input];
                    }
                    else
                    {
                        this.nodeList[this.nodeValue].innerHTML = this.input;
                    } 
                            
                    for(let i = 1; i <= 4; i++)
                    {
                        let node = this.nodeValue + i;
                        let input = this.input + i;

                        if(node > this.limit) 
                        {  
                            this.aboveInput(input, node - this.total);
                        }
                        else if(node <= this.limit) //<= ? or < ?
                        {
                            this.aboveInput(input, node);
                        }
                    }
                },

                rotateToPosition(clickedNode)
                {
                    let wheelMiddle = this.total / 2; //divide the wheel on half approx.
                    let aboveLimit;
                    let belowLimit;
      
                    if(clickedNode - this.nodeValue > 0) 
                    {
                        if(clickedNode - this.nodeValue <= wheelMiddle) 
                        { 
                            this.currentDegree += (clickedNode - this.nodeValue) * this.rotateDegree;  
                            this.input += clickedNode - this.nodeValue; //input only 1-100

                            if(this.input > this.totalValue) //input end in 100
                            {
                                aboveLimit = this.input - this.totalValue;

                                this.input = this.startValue + (aboveLimit - 1);
                            }
                        }
                        else 
                        {
                            this.currentDegree -= (this.total - (clickedNode - this.nodeValue)) * this.rotateDegree; 
                            this.input -= this.total - (clickedNode - this.nodeValue);

                            if(this.input < this.startValue) //input start from 1; //0-(-3) max; 
                            { 
                                belowLimit = this.startValue - this.input;

                                this.input = this.totalValue - (belowLimit - 1);
                            }
                        }
                    }
                    else if(clickedNode - this.nodeValue < 0) 
                    { 
                        if(this.nodeValue - clickedNode <= wheelMiddle) 
                        {
                            this.currentDegree -= (this.nodeValue - clickedNode) * this.rotateDegree;  
                            this.input -= this.nodeValue - clickedNode;

                            if(this.input < this.startValue) //input start from 1; //0-(-3) max; 
                            { 
                                belowLimit = this.startValue - this.input;

                                this.input = this.totalValue - (belowLimit - 1);
                            }
                        }
                        else 
                        {
                            this.currentDegree += (this.total - (this.nodeValue - clickedNode)) * this.rotateDegree; 
                            this.input += this.total - (this.nodeValue - clickedNode);

                            if(this.input > this.totalValue) //input end in 100
                            {
                                aboveLimit = this.input - this.totalValue;

                                this.input = this.startValue + (aboveLimit - 1);
                            }
                        }
                    }
  
                    this.nodeValue = clickedNode;	
                },

                rotateCarousel(degree) 
                {
                    document.getElementById('{{ $modelName }}').style.transform = 'rotateX(' + degree + 'deg)'; //this.currentDegree

                    //$refs.carousel.style.transform = 'rotateX(' + this.currentDegree + 'deg)';
                    //console.log($refs.carousel); //$refs doesn't work, why?
                },

                wheelChange(event) {
                    this.setInput(event);

                    this.setNodes();

                    /* if (event.deltaY < 0) { //-100; wheelEvent < 0; wheelEvent === -100
                        this.currentDegree -= this.rotateDegree;
                    } else { //100; wheelEvent === 100
                        this.currentDegree += this.rotateDegree;
                    }

                    $refs.carousel.style.transform = 'rotateX(' + this.currentDegree + 'deg)';*/

                    //Problem with $ in Alpine
                    /*$($refs.carousel).css({
                        '-webkit-transform': 'rotateX(' + currdeg + 'deg)',
                        '-moz-transform': 'rotateX(' + currdeg + 'deg)',
                        '-o-transform': 'rotateX(' + currdeg + 'deg)',
                        'transform': 'rotateX(' + currdeg + 'deg)'
                    });*/

                    //console.log('input: ' + this.input, 'nodeValue: ' + this.nodeValue, 'currentDegree: ' + this.currentDegree);
                },
                    
                clickRotate(i) 
                {
                    this.rotateToPosition(i);

                    this.setNodes();

                    this.rotateCarousel(this.currentDegree);

                    //console.log('input: ' + this.input, 'nodeValue: ' + this.nodeValue, 'currentDegree: ' + this.currentDegree); 
                    //console.log(i); //$event.target
                },

                setNodeValueDrag()
                {
                    let input = Math.round(this.currentDegree / this.rotateDegree); 

                    if(input == -0)
                    {
                        input = 0;
                    }

                    this.finalDegreeDrag = input * this.rotateDegree;

                    if(input < 0) 
                    { 
                        if(Math.abs(input) > this.total) 
                        {
                            input = Math.round(Math.abs(input) % this.total); 

                            if(input == 0)
                            {
                                input = this.total;
                            }
                        }

                        input = this.total - Math.abs(input);
                    }
                    else 
                    {
                        if(input > this.limit) 
                        {
                            input = Math.round(input % this.total); 
                        }
                    }

                    return input;
                },

                decrementInputDrag()
                {
                    if(this.input == this.startValue)
                    {
                        this.input = this.totalValue;
                    }
                    else
                    {
                        this.input--;
                    }
                },

                incrementInputDrag()
                {
                    if(this.input == this.totalValue)
                    {
                        this.input = this.startValue;
                    }
                    else
                    {
                        this.input++;
                    }
                },
  
                startDrag($event)
                {
                    $event.dataTransfer.setDragImage($event.target, window.outerWidth, window.outerHeight);
                    //$el.classList.add('cursor-default'); //doesn't work

                    $event.target.parentNode.classList.add('!duration-0'); 

                    this.dragStart = $event.clientY;

                    //set fifth elements on both sides

                    let node;
                    let input;

                    node = this.nodeValue - 5; //set only fifth element
                    input = this.input - 5;

                    if(node < 0) 
                    {  
                        this.belowInput(input, this.total + node);
                    }
                    else if(node >= 0) 
                    {
                        this.belowInput(input, node);
                    }

                    node = this.nodeValue + 5;
                    input = this.input + 5;

                    if(node > this.limit) 
                    {  
                        this.aboveInput(input, node - this.total);
                    }
                    else if(node <= this.limit) 
                    {
                        this.aboveInput(input, node);
                    } 

                    //console.log('dragstart', 'clientY: ' + $event.clientY);
                },
                
                dragging($event)
                {
                    if($event.clientY != 0)
                    {
                        this.distanceDrag = $event.clientY - this.dragStart;

                        this.dragStart = $event.clientY;

                        if(this.distanceDrag != 0)
                        {
                            let node;
                            let input;

                            this.currentDegree -= this.distanceDrag;

                            let currentNodeValue = this.setNodeValueDrag();

                            if(currentNodeValue != this.nodeValue)
                            {
                                this.nodeValue = currentNodeValue;

                                //this.setInputDrag();

                                if(this.distanceDrag > 0)
                                {
                                    //decrement, watch for going below start

                                    this.decrementInputDrag();

                                    node = this.nodeValue - 5; //set only fifth element
                                    input = this.input - 5;

                                    if(node < 0) 
                                    {  
                                        this.belowInput(input, this.total + node);
                                    }
                                    else if(node >= 0) 
                                    {
                                        this.belowInput(input, node);
                                    }
                                }
                                else
                                {
                                    //increment, watch for going above limit

                                    this.incrementInputDrag();

                                    node = this.nodeValue + 5;
                                    input = this.input + 5;

                                    if(node > this.limit) 
                                    {  
                                        this.aboveInput(input, node - this.total);
                                    }
                                    else if(node <= this.limit) //<= ? or < ?
                                    {
                                        this.aboveInput(input, node);
                                    }
                                }
                            }

                            this.rotateCarousel(this.currentDegree);
                        }
                    }

                    //console.log('drag', 'clientY: ' + $event.clientY, this.nodeValue, this.input);
                },

                endDrag($event)
                {
                    this.currentDegree = this.finalDegreeDrag;

                    this.rotateCarousel(this.currentDegree);

                    $event.target.parentNode.classList.remove('!duration-0');

                    //console.log('dragend');
                }
            }" >

                <x-dropdown>
                    <x-slot:trigger>
                        {{ $inputElement }}
                    </x-slot:trigger>

                    <div wire:ignore 

                        {{ $attributes->class(['h-53 perspective-distant transform-3d relative flex justify-items-center bg-base-100']) }} >

                        <div id="{{ $modelName }}" x-ref="carousel" @wheel.prevent="wheelChange"    
                            class="absolute top-21 left-1 transform-3d transition-transform duration-1000 flex items-center " >   {{-- //x-ref doesn't work, why? --}}

                            @php
                                $degrees = [0, 340, 320, 300, 280, 260, 240, 220, 200, 180, 160, 140, 120, 100, 80, 60, 40, 20];
                            @endphp

                            @for ($i = 0; $i < 18; $i++)
                                @if($i < 5)   
                                    <div @click.stop="clickRotate( {{ $i }} )" @dragstart="startDrag" @drag="dragging" @dragend="endDrag" class="absolute p-1 text-base-content/70 font-semibold rounded-md hover:bg-base-200 cursor-default picker-item" style="transform: rotateX({{ $degrees[$i] }}deg) translateZ(83px)" draggable="true">{{ $dataCarousel[$i] }}</div> 
                                @elseif($i < 14)
                                    <div @click.stop="clickRotate( {{ $i }} )" @dragstart="startDrag" @drag="dragging" @dragend="endDrag" class="absolute p-1 text-base-content/70 font-semibold rounded-md hover:bg-base-200 cursor-default picker-item" style="transform: rotateX({{ $degrees[$i] }}deg) translateZ(83px)" draggable="true"></div>
                                @else
                                    <div @click.stop="clickRotate( {{ $i }} )" @dragstart="startDrag" @drag="dragging" @dragend="endDrag" class="absolute p-1 text-base-content/70 font-semibold rounded-md hover:bg-base-200 cursor-default picker-item" style="transform: rotateX({{ $degrees[$i] }}deg) translateZ(83px)" draggable="true">{{ $dataCarousel[$i - 9] }}</div> 
                                @endif
                            @endfor 
                        </div> 

                        <div class="absolute top-17 h-7 w-full rounded-md bg-base-300" style="transform: translateZ(10px)"></div>

                        <x-button  @click="$wire.set( '{{ $modelName }}', textValues ? textValues[input] : input, {{ $isLive }} )" class="btn-sm self-end" label="{{ __('Set') }}" /> {{-- wire:click="{{ $setPropertyMethod }}(textValues ? textValues[input] : input)"; @click="$wire.set( '{{ $modelName }}', textValues ? textValues[input] : input, {{ $isLive }} )"; @click="$wire.setLength(input)"; inputPlaceholder = input --}}
                    </div>
 

                    {{-- wire:wheel.prevent="" --}

                    {{-- <x-menu-item title="Archive" wire:click.stop="" />
                    <x-menu-item title="Move" /> --}}
                </x-dropdown> 

                {{ $progress }}
            </div>
        blade;
    }
}
