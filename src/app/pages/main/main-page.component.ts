import {Component} from '@angular/core';
import {AppsGridComponent} from '../../components/previews/apps-grid/apps-grid.component';
import {Carousel} from '../../components/previews/carousel/carousel.component';

@Component({
  selector: 'app-main',
  imports: [AppsGridComponent, Carousel],
  templateUrl: './main-page.component.html',
  styleUrl: './main-page.component.scss'
})
export class MainPage {

}
