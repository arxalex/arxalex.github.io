import {Component, Input} from '@angular/core';
import {CarouselItemModel} from './carousel-item.model';
import {ButtonsInline} from '../../buttons-inline/buttons-inline.component';

@Component({
  selector: 'app-carousel-item',
  imports: [
    ButtonsInline
  ],
  templateUrl: './carousel-item.component.html',
  styleUrl: './carousel-item.component.scss'
})
export class CarouselItem {
  @Input() item!: CarouselItemModel;
}
