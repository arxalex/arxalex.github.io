import { Component } from '@angular/core';
import {SocialLinksListComponent} from '../social-links-list/social-links-list.component';

@Component({
  selector: 'app-footer',
  imports: [SocialLinksListComponent],
  templateUrl: './footer.component.html',
  styleUrl: './footer.component.scss'
})
export class FooterComponent {

}
