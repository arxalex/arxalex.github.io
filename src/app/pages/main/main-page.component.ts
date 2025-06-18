import {Component} from '@angular/core';
import {ProjectListComponent} from '../../components/previews/project-list/project-list.component';
import {ContactForm} from '../../components/contact-form/contact-form';

@Component({
  selector: 'app-main',
  imports: [ProjectListComponent, ContactForm],
  templateUrl: './main-page.component.html',
  styleUrl: './main-page.component.scss'
})
export class MainPage {

}
