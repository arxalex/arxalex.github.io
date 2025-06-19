import {Component, OnInit} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {CarouselItem} from './carousel-item/carousel-item.component';
import {CarouselItemModel} from './carousel-item/carousel-item.model';

@Component({
  selector: 'app-carousel',
  imports: [
    CarouselItem
  ],
  templateUrl: './carousel.component.html',
  styleUrl: './carousel.component.scss'
})
export class Carousel implements OnInit {
  carouselItems: CarouselItemModel[] = [];

  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.http.get<CarouselItemModel[]>('/featured.json')
      .subscribe(data => this.carouselItems = data);
  }
}
